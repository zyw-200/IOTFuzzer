#!/bin/sh
echo [$0] ... > /dev/console
statistic -i wifi0 &> /dev/console
statistic -i wifi1 &> /dev/console
