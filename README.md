# SOCIAL NETWORKING APPLICATION

A console-based social networking application (similar to Twitter) satisfying the scenarios below.

### Scenarios

**Posting**: Alice can publish messages to a personal timeline

> \> Alice -> I love the weather today    
> \> Bob -> Damn! We lost!     
> \> Bob -> Good game though.    

**Reading**: Bob can view Alice’s timeline

> \> Alice    
> \> I love the weather today (5 minutes ago)    
> \> Bob    
> \> Good game though. (1 minute ago)     
> \> Damn! We lost! (2 minutes ago)    

**Following**: Charlie can subscribe to Alice’s and Bob’s timelines, and view an aggregated list of all subscriptions

> \> Charlie -> I'm in New York today! Anyone wants to have a coffee?     
> \> Charlie follows Alice    
> \> Charlie wall    
> \> Charlie - I'm in New York today! Anyone wants to have a coffee? (2 seconds ago)    
> \> Alice - I love the weather today (5 minutes ago)    

> \> Charlie follows Bob    
> \> Charlie wall    
> \> Charlie - I'm in New York today! Anyone wants to have a coffee? (15 seconds ago)     
> \> Bob - Good game though. (1 minute ago)     
> \> Bob - Damn! We lost! (2 minutes ago)     
> \> Alice - I love the weather today (5 minutes ago)

### Prerequisites

Last PHP version Installed
more info: http://php.net/docs.php

Last MYSQL version installed
more info: https://dev.mysql.com/doc/

## Getting Started

* Create mysql database
* Import default tables from [db_sample.sql] provided file in your database
* Edit mysql connection parameters in [class\include\mysql_config.inc.php] file with your own

```
$MYSQL_HOST = "your mysql host";
$MYSQL_USERNAME = "your mysql username";
$MYSQL_PASSWORD = "your mysql password";
$MYSQL_DBNAME = "your database name";
```

* Optionally, edit global parameters and setting in [class\include\config.inc.php] file

If running on linux platform uncomment these lines in [index.php] file

```
include_once(__DIR__ . '/class/social_networking_class.php');
```

and these lines in [social_networking_class.php] file

```
include_once(__DIR__ . '/include/mysql_config.inc.php');
include_once(__DIR__ . '/include/config.inc.php');
```

## Running the tests

Execute [index.php] script from PHP console

**Accepted commands**

- posting: \<user name> -> \<message> 
- reading: \<user name> 
- friendship: \<user name> friendship \<another user>
- following: \<user name> follows \<another user> 
- wall: \<user name> wall 

## Versioning

Version 1.0 - 2018/08/11

## Authors

* **Gaetano Ceraulo** - *Initial work* - [SOCIAL NETWORKING APPLICATION](https://github.com/PurpleBooth)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
