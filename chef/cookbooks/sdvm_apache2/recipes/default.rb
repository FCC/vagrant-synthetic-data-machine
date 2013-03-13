#
# Cookbook Name:: apache2
# Recipe:: default
#
# Copyright 2008-2009, Opscode, Inc.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

#
# Customization by Greg Elin <greg.elin@fcc.gov
# Version 0.2 <03.13.2013>
#

include_recipe "apache2::default"

web_app "default" do
  server_name node['hostname']
  server_aliases [node['fqdn'], "localhost"]
  docroot "/vagrant/html"
end

#
# Turn off CentOS firewall
#
# execute "Turn off CentOS firewall" do
#   user "root"
#   command "service iptables stop"
#   command "chkconfig iptables off"
# end

#
# Load firewall rules we know works
#
template "/etc/sysconfig/iptables" do
  # path "/etc/sysconfig/iptables"
  source "iptables.erb"
  owner "root"
  group "root"
  mode 00600
  # notifies :restart, resources(:service => "iptables")
end

execute "service iptables restart" do
  user "root"
  command "service iptables restart"
end
