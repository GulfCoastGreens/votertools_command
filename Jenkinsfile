node ('master'){
  def nodeHome = tool name: 'nodeJS', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
  // def workspace = pwd()
  env.PATH = "${nodeHome}/bin:${env.JENKINS_HOME}/bin:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:./:${env.PATH}"
  checkout scm

  stage('Get Ansible Roles') {
      sh 'ansible-galaxy install -r ansible/requirements.yml -p ansible/roles/ -f -vvv'
  }

  stage('Run Ansible Playbook') {
      // Run the maven build
      sh "ansible-playbook -i 'localhost,' -c local ansible/playbook.yml"
      archiveArtifacts artifacts: 'deploy/votertools-*.x86_64.rpm'
  }

  // sh 'mvn clean package'
  // sh '\\cp deploy/votertools-*.x86_64.rpm $JENKINS_HOME'
}
