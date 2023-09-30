#!/bin/bash
mkdir -p ~/.screenshots
flameshot gui -p ~/.screenshots 
ls ~/.screenshots
rm -rf ~/.screenshots/*
