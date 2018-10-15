#!/bin/sh
bucket=or-ng-s3-aplog
s3Key=AKIAIBIJ626RMKREDDKA
s3Secret=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
file=$1
resource="/${bucket}/${file}"
contentType="application/x-compressed-tar"
dateValue=`date -R`
stringToSign="PUT\n\n${contentType}\n${dateValue}\n${resource}"
signature=`echo -en ${stringToSign} | openssl sha1 -hmac ${s3Secret} -binary | openssl enc -base64`
curl -k -X PUT -T "${file}" \
  -H "Host: ${bucket}.s3.amazonaws.com" \
  -H "Date: ${dateValue}" \
  -H "Content-Type: ${contentType}" \
  -H "Authorization: AWS ${s3Key}:${signature}" \
  https://${bucket}.s3.amazonaws.com/${file}
