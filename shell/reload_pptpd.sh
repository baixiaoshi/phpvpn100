#!/bin/bash
DPATH=/etc/ppp/chap-secrets
SPATH=/tmp/chap-secrets

sudo rm -rf $DPATH
sudo cp $SPATH /etc/ppp
sudo /etc/init.d/pptpd restart