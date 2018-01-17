#!/usr/bin/perl -w

# $Id: auth_ldap.pl 802 2008-04-17 20:29:24Z jberanek $

$server = shift;
$dn = shift;
$password = shift;

use Net::LDAP qw(LDAP_SUCCESS);

$ldap = Net::LDAP->new($server) or exit 1;

$msg = $ldap->bind(dn => $dn, password => $password);

exit $msg->code;
