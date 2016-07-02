require 'json'

ENV['VAGRANT_DEFAULT_PROVIDER'] = 'virtualbox'
ENV['ANSIBLE_ROLES_PATH'] = './ansible/roles'
boxes = [
  { :name => :fedora,     :box => 'fedora/23-cloud-base',    :vmname => 'fedoravotertoolsvm',   :autostart => true, :role => 'web_dev',     :ip => '192.168.33.1', :ssh_port => 2201, :http_fwd => 9980, :cpus =>4, :shares => true },
  { :name => :debian,     :box => 'deb/jessie-amd64',    :vmname => 'debianvotertoolsvm',   :autostart => false,    :role => 'data_dev',         :ip => '192.168.33.2', :ssh_port => 2202, :mysql_fwd => 9936, :cpus =>4 }
]
# Pull in the github-oauth key from logged in user
begin
  githuboauth = JSON.parse(%x( /usr/local/bin/composer global config github-oauth ))["github.com"]  
# puts githuboauth
rescue JSON::ParserError => e  
  githuboauth = ''
  puts 'GitHub Oauth not found!!!'
end 

Vagrant.configure(2) do |config|

  boxes.each do |opts|
    config.vm.define opts[:name], autostart: opts[:autostart] do |config|
      config.vm.box = opts[:box]
      config.vm.provider :virtualbox do |vb|
        vb.name = opts[:vmname]
        vb.gui = false
        vb.cpus = 1
        vb.memory = "1024"
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        vb.customize ["modifyvm", :id, "--ioapic", "on"]
      end
      config.vm.hostname = "#{opts[:name]}.votertools.vagrant.dev"
      # Set the name of the VM. See: http://stackoverflow.com/a/17864388/100134
      config.vm.define opts[:vmname] do |vm|
      end

      config.ssh.username = "vagrant"
      config.vm.network :private_network, type: "dhcp"
      config.vm.provision :ansible do |ansible|
        ansible.playbook = "ansible/playbook.yml"
        ansible.sudo = true
        # ansible.verbose = "vvv"
        ansible.groups = {
          "votertools" => ["fedoravotertoolsvm","debianvotertoolsvm"]
        }
        puts githuboauth
        ansible.extra_vars = {
          remote_user: "vagrant",
          composer_github_oauth: githuboauth
        }
      end

    end
  end


  # config.vm.provider "virtualbox"
end
