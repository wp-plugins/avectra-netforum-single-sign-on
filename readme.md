#Fsahsen/Wp-NetAuth

##Getting Started

1. Install composer by running the following commands:
```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

2. Create a directory in `wp-content` called `composer`.

3. Create a new file in `wp-content/composer` called `composer.json` and paste the following contents:
```
{
    "config" : {
        "vendor-dir" : ""
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://fsahsen@bitbucket.org/fsahsen/netforum.git"
        },
        {
            "type": "vcs",
            "url": "https://fsahsen@bitbucket.org/fsahsen/wp-bootstrap.git"
        },
        {
            "type": "vcs",
            "url": "https://fsahsen@bitbucket.org/fsahsen/wp-netauth.git"
        },
        {
            "type": "vcs",
            "url": "https://fsahsen@bitbucket.org/fsahsen/wp-netgroups.git"
        }
    ],
    "require": {
        "php": ">=5.4.0",
        "composer/installers" : "~1.0",
        "fusionspan/netforum": "dev-master",
        "fsahsen/wp-bootstrap": "dev-master",
        "fsahsen/wp-netauth": "dev-master",
        "fsahsen/wp-netgroups": "dev-master"
    },
    "extra" : {
        "installer-paths": {
            "../plugins/{$name}/": ["type:wordpress-plugin"]
        }
    },
    "minimum-stability": "dev"
}
```
4. run `composer update` composer will automatically install the necessary plugins.


## Documentation

1. Go to the plugins section on Wordpress, there should be 2 new plugins: fusionSpan | netFORUM Groups Sync, and fusionSpan | netFORUM Single Sign On.

1. Press the Activate link under both of these plugins to activate them.

3. There should now be an option on the side bar called fusionSpan. Click on it to go the setup page.

4. Under the `General` tab fill in the xWeb WSDL url, xWeb Username and xWebPassword.

5. Under the `Cache` tab the Cache Secret Key is pre-populated with random values, you may change this value if you want to. Additionally, you may change the Cache TTL to how long you want the Cache to persist in memory (in seconds). By default it is set to 86400 seconds (1 day)

6. Under the `Group` tab, you can create a custom local Wordpress group for users who are receiving benefits. To do so check the box Receives Benefits and enter a name in the Group Name field. It should be noted that this group has the highest priority and any customer who receives benefits will be put in this group, even if they match the criteria for the Members Flag group. If you want members to be stored in a special local Wordpress group you can check the member flag and enter a name in the Group Name field that appears under the Member Flag. The remaining choices provide further choices on when to add a member to the Member Flag group. For example the Having Membership Status box when checked will only add a user to the Member Flag group if their status matches that in the Status box (which appears when the box is checked). All members who do not match any of the groups above are stored as a Wordpress subscriber role.


## Contribution Guidelines

Support follows PSR-2 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page. Pull requests are welcome.
