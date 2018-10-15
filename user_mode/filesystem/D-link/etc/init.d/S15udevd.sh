#!/bin/sh
mount -t tmpfs tmpfs /dev
mkdir -p /dev/pts
mount -t devpts devpts /dev/pts
udevd --daemon
udevstart
