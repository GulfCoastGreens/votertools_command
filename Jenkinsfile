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
    sh('#!/bin/sh -e\n' + "ansible-playbook -i 'localhost,' -c local --vault-password-file=${env.env.USF_ANSIBLE_VAULT_KEY_YMD} ansible/playbook.yml --extra-vars 'target_hosts=all jenkins_home=${env.JENKINS_HOME} deploy_env=${env.DEPLOY_ENV} package_revision=${env.BUILD_NUMBER}' -t build")


      // Run the maven build
      // sh "ansible-playbook -i 'localhost,' -c local ansible/playbook.yml --extra-vars 'jenkins_home=${env.JENKINS_HOME}'"
      archiveArtifacts artifacts: 'deploy/votertools-*.x86_64.rpm'
      archiveArtifacts artifacts: 'deploy/votertools*amd64.deb'
  }

  // sh 'mvn clean package'
  // sh '\\cp deploy/votertools-*.x86_64.rpm $JENKINS_HOME'
}
