node ('master'){
  def nodeHome = tool name: 'nodeJS', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
  // def workspace = pwd()
  env.PATH = "${nodeHome}/bin:${env.JENKINS_HOME}/bin:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:./:${env.PATH}"
  checkout scm

  stage('Get Ansible Roles') {
    sh('#!/bin/sh -e\n' + 'rm -rf ansible/roles')
    sh('#!/bin/sh -e\n' + 'ansible-galaxy install -r ansible/requirements.yml -p ansible/roles/ -f')
  }

  stage('Build VoterTool Command-line tool') {
    sh('#!/bin/sh -e\n' + "ansible-playbook -i 'localhost,' -c local --vault-password-file=${env.YMD_ANSIBLE_VAULT_KEY} ansible/playbook.yml --extra-vars 'target_hosts=all jenkins_home=${env.JENKINS_HOME} deploy_env=${env.DEPLOY_ENV} package_revision=${env.BUILD_NUMBER}' -t build")
    archiveArtifacts artifacts: 'deploy/votertools-*.x86_64.rpm'
    archiveArtifacts artifacts: 'deploy/votertools*amd64.deb'
  }
  stage('Deploy and setup VoterTool Command-line tool') {
    sshagent (credentials: ['operations']) {
      sh('#!/bin/sh -e\n' + "ansible-playbook -i ansible/roles/inventory/${env.DEPLOY_ENV.toLowerCase()}/hosts --user=jenkins --vault-password-file=${env.YMD_ANSIBLE_VAULT_KEY} ansible/playbook.yml --extra-vars 'target_hosts=${env.DEPLOY_HOST} deploy_env=${env.DEPLOY_ENV} package_revision=${env.BUILD_NUMBER}' -t deploy -vvv")
    }
  }
  stage('Run the data state for VoterTool Command-line tool') {
    sshagent (credentials: ['operations']) {
      sh('#!/bin/sh -e\n' + "ansible-playbook -i ansible/roles/inventory/${env.DEPLOY_ENV.toLowerCase()}/hosts --user=jenkins --vault-password-file=${env.YMD_ANSIBLE_VAULT_KEY} ansible/playbook.yml --extra-vars 'target_hosts=${env.DEPLOY_HOST} deploy_env=${env.DEPLOY_ENV} package_revision=${env.BUILD_NUMBER}' -t data -vvv")
    }
  }
}
