# votertools_command

VoterTools Command Line does some matching operations against a voter registration database. 

Importing Voters

Florida Voters Example

```
votertools.phar florida:voterimport --tmp ~/ --config ~/config/ gpfl ~/voters.zip 
```

Importing Histories

Florida Histories Example

```
votertools.phar florida:historyimport --tmp ~/ --config ~/config/ gpfl ~/histories.zip 
```

Syncing CiviCRM

Florida Sync SQL

```
votertools.phar florida:civiupdate --config ~/config/ --voterkey voter_id_civi_custom gpfl ~/voterids.json 
```
