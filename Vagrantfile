# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
  
  #
  # Configure  VM - Apache2, PHP5
  #
    # config.vm.boot_mode = :gui
    config.vm.box = "centos-6.3-nrel"
    config.vm.box_url = "http://developer.nrel.gov/downloads/vagrant-boxes/CentOS-6.3-x86_64-v20130101.box"
    config.vm.forward_port 80, 8080
   
    config.vm.provision :chef_solo do |chef|
      chef.cookbooks_path = "chef/cookbooks"
      chef.roles_path = "chef/roles"
      chef.add_role "base"
      chef.add_recipe "sdvm_apache2"
      chef.add_recipe "php"
      chef.add_recipe "mysql::server"
      chef.add_recipe "mysql::client"
      # chef.add_recipe "php::module_mysql"

      # Configure mysql passwords
      chef.json = {
        :mysql => {
          :server_root_password => "secret0",
          :server_repl_password => "secret0",
          :server_debian_password => "secret0" 
          },
        :mysql_password => "secret0"
      }

    end

end
