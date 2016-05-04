#! /bin/sh

if [ ! -d /usr/local/etc/votertools ]; then
    mkdir -p /usr/local/etc/votertools
fi
if [ ! -f /usr/local/etc/votertools/votertools.yml ]; then
    echo "File not found!"
    CONFIGFILE="/usr/local/etc/votertools/votertools.yml"
    /bin/cat <<EOM >$CONFIGFILE
gcgDatabase:
    gpfl:
        database_type: mysql
        database_name: gpfl
        server: localhost
        username: root
        password: 
        port: 3306
votertools:
    voter:
        florida:  
            civicrm:
                tablename: civicrm_value_fl_voter_id_1
                historytablename: civicrm_value_floridahistories_3
                partyhistorytablename: civicrm_value_florida_registrations_4
                voterIDfield: fl_voter_id_1
                partyhistoryFieldMap:
                    county_code: registered_couny_128
                    party_affiliation: party_affiliation_129
                    export_date: reported_date_130
                historyFieldMap:
                    county_code: registered_county_123
                    election_date: election_date_125
                    election_type: election_type_126
                    history_code: history_description_127
                voterFieldMap:
                    county_code: registered_county_3
                    voter_id: fl_voter_id_1
                    name_last: last_name_4	
                    name_suffix: suffix_5
                    name_first: first_name_7
                    name_middle: middle_name_8
                    suppress_address: suppress_address_9
                    residence_address_line_1: residence_address_line_1_10
                    residence_address_line_2: residence_address_line_2_11
                    residence_city: residence_city_12
                    residence_state: residence_state_13
                    residence_zipcode: resident_zipcode_14
                    mailing_address_line_1: mailing_address_line_1_15
                    mailing_address_line_2: mailing_address_line_2_16
                    mailing_address_line_3: mailing_address_line_3_17
                    mailing_city: mailing_city_18
                    mailing_state: mailing_state_41
                    mailing_zipcode: mailing_zipcode_19
                    mailing_country: mailing_county_20
                    gender: gender_21
                    race: race_22
                    birth_date: birthdate_24
                    registration_date: registration_date_25
                    party_affiliation: party_affiliation_23
                    precinct: precinct_26
                    precinct_group: precinct_group_27
                    precinct_split: precinct_split_28
                    precinct_suffix: precinct_suffix_29
                    voter_status: voter_status_30
                    congressional_district: congressional_district_31
                    house_district: house_district_32
                    senate_district: senate_district_33
                    county_commission_district: county_commission_district_34
                    school_board_district: school_board_district_35
                    daytime_area_code: daytime_area_code_36
                    daytime_phone_number: daytime_phone_number_37
                    daytime_phone_extension: daytime_phone_extension_38
                    export_date: current_export_date_39
        georgia: 
EOM
fi
