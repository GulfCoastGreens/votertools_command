node ('master'){
  stage 'Build and Test'
  def nodeHome = tool name: 'nodeJS', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
  def workspace = pwd()
  env.PATH = "${nodeHome}/bin:${workspace}:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:./:${env.PATH}"
  checkout scm
  sh 'curl -sS https://getcomposer.org/installer | php'
  sh 'mv composer.phar composer'
  sh 'npm install'
  sh 'node_modules/grunt-cli/bin/grunt build'
  // sh 'mvn clean package'
  sh '\\cp deploy/votertools-*.x86_64.rpm $JENKINS_HOME'
}
