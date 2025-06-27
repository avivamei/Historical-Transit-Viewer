# Historical Transit Viewer

## Setup instructions

### user/password db auth secrets

Make a copy of `secrets.example.php` named `secrets.php` and replace the data with your own username and password. 

At the beginning of any relevant file, put `<?php include 'secrets.php'; ?>` which will put the secrets into the environment.

When writing a page, use something like `<?php $username_variable = getenv('ORACLE_USERNAME')` to access the env.

### testing

Test all SQL stuff by copying the app to your the ubc cs servers:

`scp -r 304-app yourcwl@remote.students.cs.ubc.ca:public_html`

Apache server is running and will serve any stuff you put in your public_html folder at the url

`https://www.students.cs.ubc.ca/~yourcwl/304-app/...`

Alternatively, push your changes to github, ssh into the servers, and pull from github.

Note you'll have to change permissions on things for the app to be live on the internet. 
`ssh yourcwl@remote.students.cs.ubc.ca`
`chmod 711 ~`
`chmod 711 public_html`
`chmod 711 public_html/*`

and if you add files, you'll need to chmod those as well. 

