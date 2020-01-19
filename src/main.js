import chalk from 'chalk';
import execa from 'execa';
import fs from 'fs';
import Listr from 'listr';
import ncp from 'ncp';
import path from 'path';
import { projectInstall } from 'pkg-install';
import { promisify } from 'util';
import slugify from 'slugify';
import config from './config.json';
import replaceInFiles from 'replace-in-file';
import notifier from 'node-notifier';
import open from 'open';

const access = promisify(fs.access);
const writeFile = promisify(fs.writeFile);
const copy = promisify(ncp);

async function copyTemplateFiles(options) {
  return copy(options.templateDirectory, options.targetDirectory + '/' + options.slug, {
    clobber: false,
  });
}

async function createGulp(options) {
    const targetPath = path.join(options.targetDirectory + '/' + options.slug, 'wpgulp.config.json');
    const content = config.gulp
    .replace('<projecturl>', options.slug + '.local')
    .replace(/<slug>/g, options.slug);
   
    return writeFile(targetPath, content, 'utf8');
}

async function renameFiles(options) {
  fs.rename(options.targetDirectory + '/' + options.slug + '/languages/wordpress-template-theme.pot' , options.targetDirectory  + '/' + options.slug + '/languages/' + options.slug + '.pot', function (err) {
    if (err) throw err;
  });
}

async function createCSS(options) {
    const targetPath = path.join(options.targetDirectory + '/' + options.slug, 'style.css');
    const content = config.style
    .replace('<themename>', options.themename);
  
    return writeFile(targetPath, content, 'utf8');
}

async function initGit(options) {
  const result = await execa('git', ['init'], {
    cwd: options.targetDirectory + '/' + options.slug,
  });
  if (result.failed) {
    return Promise.reject(new Error('Failed to initialize git'));
  }
  return;
}

async function replaceName(options) {

  const replaceOptions = {
    files: options.targetDirectory + '/' + options.slug + '/**/*',
    from: [/wordpress_template_theme/g, /wordpress-template-theme/g, /wordpress template theme/g ],
    to: [options.underscore, options.slug, options.themename],
  };

  try {
    await replaceInFiles(replaceOptions);
  }
  catch (error) {
    console.error('Error occurred:', error);
  }
}
 
export async function createProject(options) {
  options = {
    ...options,
    targetDirectory: options.targetDirectory || process.cwd(),
    email: 'richard@richardmiddleton.me',
    name: 'Richard Middleton',
    themename: options.themename,
    slug: slugify(options.themename.toLowerCase()),
    underscore: slugify(options.themename.toLowerCase()).replace(/-/g, '_')
  };

  const templateDir = path.resolve(
    new URL(import.meta.url).pathname,
    '../../templates',
    options.template
  );
  options.templateDirectory = templateDir;
  try {
    await access(templateDir, fs.constants.R_OK);
  }
  catch (err) {
    console.error('%s Invalid template name', chalk.red.bold('ERROR'));
    process.exit(1);
  }

  const tasks = new Listr([
      {
        title: 'Copy project files',
        task: () => copyTemplateFiles(options),
      },
      {
        title: 'Create Gulp',
        task: () => createGulp(options),
      },
      {
        title: 'Create CSS',
        task: () => createCSS(options),
      },
      {
        title: 'Initialize git',
        task: () => initGit(options),
        enabled: () => options.git,
      },
      {
        title: 'Replacing Names',
        task: () => replaceName(options),
      },
      {
        title: 'Rename Filenames',
        task: () => renameFiles(options),
      },
      {
        title: 'Install dependencies',
        task: () =>
        projectInstall({
          cwd: options.targetDirectory + '/' + options.slug,
        }),
        skip: () =>
          !options.runInstall ? 'Pass --install to automatically install dependencies' : undefined,
      }],
    {
      exitOnError: false,
    }
  );

  await tasks.run();
  console.log('%s Theme ready', chalk.green.bold('DONE'));
  
  notifier.notify({
    title: 'Theme Ready',
    subtitle: 'Your theme has been created in',
    message: options.targetDirectory + '/' + options.slug,
    icon: path.join(__dirname, 'icon.png'),
    sound: 'Glass',
    actions: 'Open Folder',
    closeLabel: 'Cancel',
    reply: false,
    timeout: 1
  },
  function() {
    open(options.targetDirectory);
  }
);
  return true;
}
