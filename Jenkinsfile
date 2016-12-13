node ('master'){
  def nodeHome = tool name: 'nodeJS', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
  def workspace = pwd()
  env.PATH = "${nodeHome}/bin:${workspace}:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:./:${env.PATH}"
  checkout scm
  stage('Get PHP Composer') {
      sh 'curl -sS https://getcomposer.org/installer | php'
      sh 'mv composer.phar composer'
  }
  stage('Install Nodejs dependencies') {
      sh 'npm install'
  }
  stage('Build with Grunt') {
      sh 'node_modules/grunt-cli/bin/grunt build'  
      // Archive the build output artifacts.
      archiveArtifacts artifacts: 'deploy/votertools-*.x86_64.rpm'
  }

  // sh 'mvn clean package'
  // sh '\\cp deploy/votertools-*.x86_64.rpm $JENKINS_HOME'
}
