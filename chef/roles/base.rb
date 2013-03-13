name "base"
description "Base role applied to all nodes."
run_list(
  "recipe[motd-tail]",
  "recipe[openssl]",
  "recipe[vim]"
  # "recipe[apt]",
  # "recipe[nagios::client]",
  # "recipe[chef-client::delete_validation]",
  # "recipe[sudo]",
  # "recipe[ntp]",
  # "recipe[users::sysadmins]"
  # "recipe[credentials]"
)

default_attributes(
)
