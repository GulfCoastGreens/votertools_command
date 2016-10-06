node ('master'){
  stage 'Build and Test'
  env.PATH = "${tool 'node-5.10.1'}/bin:${env.PATH}"
  
  // env.PATH = "${tool 'Maven 3'}/bin:${env.PATH}"
  checkout scm
  sh 'npm install'
  // sh 'mvn clean package'
}
