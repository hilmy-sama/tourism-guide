FROM jasonrivers/nagios:latest

RUN apt-get update && apt-get install -y \
    nagios-plugins-basic \
    nagios-plugins-standard \
 && rm -rf /var/lib/apt/lists/*
