## @file led.sh
## @Author Andrew Tong
## @Date 2013-06-14
## @brief Led Control
## @param 0 ./led.sh 0 not support
## @param 1 ./led.sh 1 fail
## @param 2 ./led.sh 2 process
## @param 3 ./led.sh 3 success
## @param 4 ./led.sh 4 finish

case $1 in
    820)
	case $2 in
	0)
	        echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 40 > /proc/gpio_led;
		echo 21 > /proc/gpio_led;echo 41 > /proc/gpio_led;echo 0;;
        1)
	        echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 40 > /proc/gpio_led;
		echo 21 > /proc/gpio_led;echo 41 > /proc/gpio_led;echo 1;;
        2)
	        echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 40 > /proc/gpio_led;
		echo 14 > /proc/gpio_led;echo 2;;
        3)
	        echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 40 > /proc/gpio_led;
		echo 14 > /proc/gpio_led;echo 3;;
        4)
	        echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 40 > /proc/gpio_led;
		echo 11 > /proc/gpio_led;echo 4;;
        esac;;
    813)
	case $2 in
	    0)
		echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 0;;
	    1)
		./led.sh $1 0;
		echo 14 > /proc/gpio_led;echo 30 > /proc/gpio_led;echo 1;;
	    2)
		./led.sh $1 0;
		echo 14 > /proc/gpio_led;echo 31 > /proc/gpio_led;echo 2;;
	    3)
		./led.sh $1 0;
		echo 10 > /proc/gpio_led;echo 34 > /proc/gpio_led;echo 3;;
	    4)
		./led.sh $1 0;
		echo 11 > /proc/gpio_led;echo 34 > /proc/gpio_led;echo 4;;
	esac;;
    731711)
	case $2 in
	    0)
		echo 10 > /proc/gpio_led;echo 20 > /proc/gpio_led;echo 0;;
	    1)
		./led.sh $1 0;echo 1;;
	    2)
		./led.sh $1 0;
		echo 14 > /proc/gpio_led;echo 24 > /proc/gpio_led;echo 2;;
	    3)
		./led.sh $1 0;
		echo 11 > /proc/gpio_led;echo 21 > /proc/gpio_led;echo 3;;
	    4)
		./led.sh $1 0;
		echo 11 > /proc/gpio_led;echo 21 > /proc/gpio_led;echo 4;;
	esac;;
    870)
	case $2 in
	    0)
		echo 0 > /sys/class/gpio/gpio49/value;
		echo 1 > /sys/class/gpio/gpio50/value;
		echo 0 > /sys/class/leds/internet:green/brightness;
		echo 1 > /sys/class/leds/internet:orange/brightness;
		echo 1 > /sys/class/gpio/gpio83/value;
		echo 1 > /sys/class/gpio/gpio84/value;
		echo 1 > /sys/class/gpio/gpio51/value;echo 0;;
	    1)
		./led.sh $1 0;echo 1;;
	    2)
		echo 1 > /sys/class/gpio/gpio49/value;
		echo 1 > /sys/class/gpio/gpio50/value;
		echo 0 > /sys/class/leds/internet:orange/brightness;
		echo 1 > /sys/class/gpio/gpio83/value;
		echo 1 > /sys/class/gpio/gpio84/value;
		echo 1 > /sys/class/gpio/gpio51/value;
		echo timer > /sys/class/leds/internet:green/trigger;
		echo 500 > /sys/class/leds/internet:green/delay_on;
		echo 250 > /sys/class/leds/internet:green/delay_off;
		echo 2;;
	    3)
		./led.sh $1 2;echo 3;;
	    4)
		echo 1 > /sys/class/gpio/gpio49/value;
		echo 0 > /sys/class/gpio/gpio50/value;
		echo 0 > /sys/class/leds/internet:green/brightness;
		echo 0 > /sys/class/leds/internet:orange/brightness;
		echo 1 > /sys/class/gpio/gpio83/value;
		echo 1 > /sys/class/gpio/gpio84/value;
		echo 1 > /sys/class/gpio/gpio51/value;
		echo 4;;
	esac	    
esac
exit 0

