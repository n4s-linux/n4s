#!/bin/bash
curl -X POST \
  https://api.nordicapigateway.com/v1/authentication/tokens \
  -H 'Content-Type: application/json' \
  -H 'X-Client-Id: ledger-7dfd59fa-ea9f-4a6d-99e7-1c0e3d0f5553' \
  -H 'X-Client-Secret: 82d27d28fc7b7b1fe8619873e3f1582ca1a9db99f237b2384a46eddbc0b65e3d' \
  -d "{
        "code": \"$1\"
      }"

