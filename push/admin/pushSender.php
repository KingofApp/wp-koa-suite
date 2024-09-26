<div id="pushStatus">
		<span class="success">Push send success</span>
		<span class="error">Push send Error: </span>
	</div>
   <div id="pushSender">
	  <label for="koa-push">Notification title:</label>
	  <input type="text" id="title">
	  <label for="koa-push">Notification text:</label>
	  <input type="text" id="body">
	  
	  <button class="btn" type="button" onclick="sendPush()">Send</button>
</div>

<script>
    async function sendPush() {
        try {
            // Validate the user ID
            if (!window.koa_push_single_user_id || isNaN(window.koa_push_single_user_id) || window.koa_push_single_user_id <= 0) {
                console.error("Invalid user ID");
                alert("Invalid user ID.");
                return null;
            }

            // Get the title and body inputs from the DOM
            const title = document.getElementById("title").value.trim();
            const body = document.getElementById("body").value.trim();

            // Validate the input fields
            if (!title || !body) {
                console.error("Title and body cannot be empty.");
                alert("Please fill in both the title and body fields.");
                return null;
            }

            // Prepare the request options for the fetch call
            const requestData = {
                user_id: window.koa_push_single_user_id,
                title: title,
                body: body
            };

            const requestOptions = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(requestData)
            };

            // Make the fetch call to the REST API
            const response = await fetch("/wp-json/firebase/v1/send-notification/", requestOptions);

            // Check if the response is not OK (e.g., status is not 2xx)
            if (!response.ok) {
                const errorData = await response.json();
                console.error("Error response from server:", errorData);
                alert(`Failed to send push notification: ${errorData.message || "Unknown error"}`);
                return null;
            }

            // Parse and handle the response
            const responseData = await response.json();
            console.log("Push notification sent successfully:", responseData);

            // Show success message to the user
            alert("Push notification sent successfully.");
            return responseData;

        } catch (error) {
            // Handle network or other unexpected errors
            console.error("Network error or unexpected issue:", error);
            alert("An error occurred while sending the push notification. Please try again.");
            return null;
        }
    }  
	
</script>