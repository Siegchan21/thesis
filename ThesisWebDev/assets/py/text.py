import requests

# URL of the PHP script
url = 'http://localhost/ThesisWebDev/assets/php/backend.php'

# Send POST request
response = requests.post(url)

# Print response content
print("Response Content:", response.text)

# Check if the request was successful (status code 200)
if response.status_code == 200:
    try:
        # Parse the JSON response
        result = response.json()
        
        # Process the received data
        # Example: print the received data
        print("Parsed JSON:", result)
    except ValueError as e:
        print("Error parsing JSON:", e)
else:
    print("Error:", response.status_code)