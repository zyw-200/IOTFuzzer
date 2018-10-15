CC=/opt/cross/mipsel-linux-musl/bin/mipsel-linux-musl-gcc
CFLAGS=-O2 -Wall
LDFLAGS=-static

OBJECTS=$(SOURCES:.c=.o)
SOURCES=single_httpd_sample_user_mode.c 
TARGET=single_httpd_sample_user_mode

all: $(SOURCES) $(TARGET)

$(TARGET): $(OBJECTS)
	$(CC) $(LDFLAGS) $(OBJECTS) -o $@

.c.o:
	$(CC) -c $(CFLAGS) $< -o $@

clean:
	rm -f *.o console

.PHONY: clean
