import arg from 'arg';
import inquirer from 'inquirer';
import { createProject } from './main';

function parseArgumentsIntoOptions(rawArgs) {
  const args = arg(
    {
      '--themename': Boolean,
      '--git': Boolean,
      '--yes': Boolean,
      '--install': Boolean,
      '-n': '--themename',
      '-g': '--git',
      '-y': '--yes',
      '-i': '--install',
    },
    {
      argv: rawArgs.slice(2),
    }
  );
  return {
    skipPrompts: args['--yes'] || false,
    themename: args['--themename'] || false,
    git: args['--git'] || false,
    template: args._[0],
    runInstall: args['--install'] || false,
  };
}

async function promptForMissingOptions(options) {
  const defaultTemplate = 'wordpress';
  if (options.skipPrompts) {
    return {
      ...options,
      template: options.template || defaultTemplate,
    };
  }

  const questions = [];
  if (!options.template) {
    questions.push({
      type: 'list',
      name: 'template',
      message: 'Please choose which project template to use',
      choices: ['wordpress', 'woocommerce'],
      default: defaultTemplate,
    });
  }

  if (!options.themename) {
    questions.push({
      type: 'input',
      name: 'themename',
      message: 'Theme Name?',
      default: false,
    });
  }

  if (!options.git) {
    questions.push({
      type: 'confirm',
      name: 'git',
      message: 'Should a git be initialized?',
      default: false,
    });
  }

  const answers = await inquirer.prompt(questions);
  return {
    ...options,
    template: options.template || answers.template,
    git: options.git || answers.git,
    themename: options.themename || answers.themename,
  };
}

export async function cli(args) {
  let options = parseArgumentsIntoOptions(args);
  options = await promptForMissingOptions(options);
  await createProject(options);
}

// ...
