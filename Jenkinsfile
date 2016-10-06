node ('master'){
  stage 'Build and Test'
  def nodeHome = tool name: 'nodeJS', type: 'jenkins.plugins.nodejs.tools.NodeJSInstallation'
  env.PATH = "${nodeHome}/bin:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:${env.PATH}"
  checkout scm
  sh 'npm install'
  sh 'node_modules/grunt-cli/bin/grunt build'
  // sh 'mvn clean package'
}
