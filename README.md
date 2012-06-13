jnet-version-checker
====================

### What?

The jnet-version-checker is an effort of JNET's WG Hosting to come up with a way to check the servers for outdated packages of open source Content Management Systems and Frameworks like:
- Wordpress
- Drupal
- Joomla
- phpBB
- Gallery
- ...


### Goal

The goal is to have one script that detects outdated packages, and sends e-mails to JNET and to the customers to update their software.


### Input

The scripts require a version input, when multiple versions are used in the community the version with the highest value comes first.
e.g. ./joomla.sh 2.3.1 1.4.23

### Output

The output is a list of directories containing an outdated version.
