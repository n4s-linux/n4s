import sys
import requests

url = "https://api.taggun.io/api/receipt/v1/verbose/file"

files = { "file": (sys.argv[2], open(sys.argv[2], "rb"), "application/pdf") }
payload = {
    "refresh": "false",
    "incognito": "false",
    "extractTime": "false"
}
headers = {
    "accept": "application/json",
    "apikey": sys.argv[1]
}

response = requests.post(url, data=payload, files=files, headers=headers)

print(response.text)
