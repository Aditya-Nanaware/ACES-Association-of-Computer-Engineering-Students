<?php
require("../fpdf/fpdf.php");

// Your full daily report text goes here:
$text = <<<EOT
Daily Report 
2nd February 2025
Successful integrated JEETO11 in PC without any errors.
Task Given: In app Advertisement pop up.
Added the Event Section.After clicking on the event section it will give pop up of event,On tap of the Event website url is given,It will navigate to Respective website .

Daily Report
7th February 2025
Created Event pop up page (Advertisement page), which will get displayed Automatically after verification of the mpin.
Created database manually added data to that in Xampp.
Created simple login page using html css and tried to connect database to it

Daily Report
8th February 2025
Created an registration form using HTML,CSS and PHP, Data stored in MySQL databases created in Xampp so that after filling the registration form data will save in database automatically.
Created an simple login page as a practice of Shared Preferences so that login data will stored locally and no need to be logged in everytime.

Daily Report
12th February 2025
Tired to solve the version errors about java,gradel and flutter

Daily Report
13th February 2025
Tried to solve the errors about version conflict, reinstalled java sdk set the environment variables again, reinstalled Android studio but still error doesn't solved.
Integrated the jeeto11 app backend code in the pc and understanding the code.

Daily Report
14th February 2025
Solved the errors about version conflict that was occuring from last two days.
Successful implemented the ui of popup page and opened using localhost.
Tried to understand the backend of the jeeto11 app.

Daily Report
15th February 2025
Successful created the page for adding the banners for pop up,There is the section for adding the banner file in which we can add image as well as video and below that section for url to redirect that respective website.There is a list of previously added banners there is the option for editing or deleting the banners.
After uploading the banners it will get stored in the database and on editing or deleting the banner it get edited and deleted from the databases also .

Daily Report
17th February 2025
Tried to understand the jeeto11 code.
Manually Created database of JEETO11.
Worked on dynamic popup advertisement.

Daily Report 
18th February 2025
Tasks Done:
Continued development of dynamic popup advertisement logic.
Added functionality to retrieve and display banners dynamically from the database.
Started working on backend validation and filtering logic for displaying only active banners.
Basic testing of the popup advertisement page with multiple banner types (image/video).

Daily Report 
21st February 2025
Tasks Done:
Integrated dynamic popup advertisement into the dashboard page.
Refined popup logic: now only active banners are shown based on current date/time from database.
Added fade-in animation to the advertisement pop-up to improve user experience.

Daily Report 
22nd February 2025
Tasks Done:
Refactored dynamic popup code for better reusability.
Extracted popup display logic into a separate controller and view for upcoming CodeIgniter integration.
Started preparing environment for CodeIgniter on localhost (created folders, configured .htaccess and base URL).

Daily Report
24th February 2025
Error was occuring to the MySQL in the Xampp so reinstalled the xampp and created the database from beginning for the previously integrated dynamic popup ui.
Downloaded the Codeigniter framework.
Integrated the dynamic pop up ui in Codeigniter in MVC structure.
Error occurred while running it on the localhost .

Daily Report
25th February 2025
Successfully integrated dynamic popup code in the Codeigniter in MVC structure.
Connected the code with database so that changes can be observed in the database as per the Activity.
Downloaded the stable version sdk of flutter and integrated the chacha rides driver code in the pc.

Daily Report
26th February 2025
Integrated the chachacabs app and sync it in android studio.
Started working on the ui correction.
Tried to fetch the user data from signup Page to profile page.

Daily Report
27th February 2025
Worked on the chacha cabs code , Fetched the user data Name,Phone number,email address in the profile page.After registration or logged in on the user the data will be Fetched on the profile page .

Daily Report
28th February 2025
Successful fetched the document and information of the user from database to the profile page.
User can update the data as per the requirement and data will also update in the Database.

Daily Report
1st March 2025
Solved the errors occurred during fetching the data about documents of the user and profile photo.
Worked on the updating the documents of the user and store updated documents in the database.

Daily Report
3rd March 2025
Worked on the Edit Profile page.
Successfully fetching the data from the Database as the user logged in.
Tried to update the data from the Edit page.

Daily Report
4th March 2025
Successfully fetching the data on the Edit Profile page from the back-end and After updating the data also updated in the database.
Worked on the UI of the Home Page to meet some requirements about the Functionality.

Daily Report
5th March 2025
Worked on Map search Bar, Location will display automatically after searching that location.
Worked on Ui part for alignment of the text and some widgets.

Daily Report
6th March 2025
Working on connection of the rider and driver app.

Daily Report
7th March 2025
Learning about connecting two applications together to communicate with each other using firebase.

Daily Report
13th March 2025
Tasks Done:
Continued Firebase integration and debugging synchronization issues between driver app and Firebase backend.
Tested driver login and authentication flows using Firebase.
Challenges:
Sync issues causing delayed updates in driver app.

Daily Report
17th March 2025
Developed a Wallet screen UI displaying balance, recent transactions, and an "Add Money" button.
- Improved the UI with gradient backgrounds, card-based layouts, and enhanced typography.
- Refactored code for better readability and performance.
learning about connecting the two different apps together.

Daily Report
18th March 2025
ISSUE:-
Composer fails to install kreait/firebase-php due to SSL certificate verification errors(certificate verify failed, getcomposer.org failed).
Actions Taken:-
1. Updated php.ini with openssl.cafile and curl.cainfo.
2. Disabled Norton Security Ultra (firewall & HTTPS scanning).
3. Tried disabling TLS via Composer config.
4. Attempted offline/standalone composer.phar install.
5. Verified Apache restart and internet access.
Still same error is occurring.

Daily Report 
19th March 2025
Progress on real-time communication for the Chachacabs rider and driver apps:
1.Resolved yesterday's Composer installation issue .
2. Set up Composer for Firebase integration in PHP.
3. Fixed Java, Kotlin, and Gradle compatibility issues affecting the app.
5. Enabled FCM token generation for sending ride details between apps.
5. Implemented a working notification bridge API (notification_bridge.php) for messaging via the PHP backend.
PFA the screenshot of successful testing.

Daily Report
20th March 2025
1. Real-Time Ride Data Sharing
   - Successfully implemented functionality that enables real-time sharing of ride data from the rider app to the driver app.
   - Ensured seamless data transmission between both apps, allowing drivers to receive live updates about ride requests and statuses.
2. Backend Integration for Ride Data Storage & Retrieval
   - Developed and optimized the storage mechanism for real ride data in the backend.
   - Implemented efficient retrieval methods to fetch only relevant ride details, ensuring that drivers receive accurate and up-to-date ride information.
3. UI Enhancements and Bug Fixes for Chachacabs Driver App
   - Improved the UI for certain modules to enhance user experience, making the interface cleaner and more intuitive.
   - Fixed layout inconsistencies, improved visual clarity, and ensured smoother navigation across the updated sections of the app.

Daily Report
21 March 2025
Passed dynamic ride details (pickup, destination, fare) from DriverScreen to RidePage.
- Updated RidePage to accept ride data via constructor.
- Replaced hardcoded values with dynamic ride data.
- Automated date using 'Datetime.now()'
- Successfully tested navigation and data display.

Daily Report
22nd March 2025
Worked on Firebase integration for real-time ride requests and driver status updates.
Tested driver login and authentication flows with Firebase backend.
Began debugging synchronization issues between driver app and database

Daily Report
23rd March 2025
Tasks Done:
Implemented ride request updates to notify riders when a driver accepts the request.
Studied methods to create centralized server for unified rider-driver communication.
Updated map UI to display route from source to destination upon ride acceptance.
Challenges:
Integrating routing API smoothly with Firebase updates.

Reporting on 24th March 2025
1. Implemented the functionality to get the updates for the ride which has been requested by the rider in rider app. So, when a driver accepts the request, it will be reflected in the rider app to notify the corresponding rider, who has requested the ride.
2. Explored and studied the different possible methods to establish a centralized server, so that the one suitable way can be opted in order to have a common base for rider and driver app.
3. Worked on updating the map UI to show the route from source to destination when the ride is accepted.

Reporting on 25th March 2025
1. Worked on updating the map UI and making it dynamic to show the route from source to destination when the ride is accepted, in the driver app.
2. Modified the ride accept and reject functionalities in order to make them work more finely.

Reporting on 26th March 2025
1. Added a new page named RideTrackingScreen build UI for that page after accepting the ride navigated to that page.
2. Studied the ways to implement the functionality for finding the drivers who are nearby the location of rider who has made the ride request, and send request to the selected drivers only.

Reporting on 27th March 2025
1. Added the new page to show before verifying the user and actually starting the ride.
2. Implemented the functionality to confirm the pickup, so that driver can proceed to verify rider's identity.
3. Added the options for drivers to start navigation from source to destination, recenter to the current location as well as enable the GPS tracking.
4. Worked on storing the driver's location to the realtime database in order to track the ride.
5. Refer the screenshot

Daily Report
28th March 2025
1. Implementation: Integrated a status update feature that changes the user’s status to "Offline"when the app is closed. This ensures real-time accuracy in user availability.
2. Testing & Validation: Successfully tested the feature by closing and reopening the app. The status updates correctly from "Online" to "Offline" and vice versa.
3. Outcome: The feature is now working as expected, providing a dynamic and reliable user status system.
4\. Implemented the functionality for storing the driver location data to firebase real time database for real time ride tracking and driver location updates.
Errors occurring:
Facing issues in rejecting ride. If one driver rejects ride, it's getting deleted from feed of all the other drivers.

Reporting on 29th March 2025
1. Worked on updating the user profile to allow users to edit the profile image along with the other details.
2. Successfully established the connection between Frontend and Backend API with the use of proper request method.
3. Now able to send the data (image as well) to the backend.
4. Backend code properly accepting the sent data.
5. Yet to modify the code to make it able to handle the file data appropriately and store it to the backend.
6. Modified the UI for user profile page to make it dynamic and more interactive.
7.solved the yesterday's issue about reject ride request.

Daily Report 1st April 2025
Issues Identified:
1. Slow performance when clicking "Save Changes" in the profile update.
2. Delay in API response affecting smooth refresh.
Debugging & Work Done:
1. Checked API request, response handling, and SharedPreferences operations.
2. Implemented refresh functionality to fetch and update user profile data.
3. Ensured UI state updates dynamically after fetching data.
4. Verified field updates, file uploads, and network performance.
Challenges Faced:
1. Network latency and server response time.
2. Large image file upload slowing down requests.
3. UI blocking during profile updates and refresh.
Fixes & Optimizations:
1. Used setState() correctly to refresh UI.
2. Added a loading indicator for better user experience.
3. Optimized API calls and reduced unnecessary delays.
4. Planned image compression before upload to improve speed.

Reporting on 2nd April 2025
Worked on Pre-booking Rides Page.
Realtime Ride Request fetching.
UI Enhancements:
1. Improved the PreBookingPage layout for better user experience.
2. Implemented a card-based structure for displaying user booking requests.
3. Added a refresh button to update the booking list dynamically.
Status Update Feature:
1. ntegrated Accept/Reject buttons for managing bookings.
2. Clicking Accept or Reject updates the booking status accordingly.
Code Optimization:
1. Enhanced code readability and maintainability by organizing elements effectively.

Reporting on 3rd April 2025
Fixed Issue: Rejected Ride Requests Reappearing After Refresh
Issue:
When a driver rejected a ride request, it was removed from the UI.
However, after refreshing the page, the rejected ride requests fetched from Firebase (added by web users) were reappearing.
Fix Implemented:
Maintained a Local List (rejectedRides)
Introduced a Set<String> rejectedRides = {}; to store rejected ride IDs.
This ensures that previously rejected rides are not added again after refreshing.
Updated listenForRideRequests()
  Before Fix:
    The function was fetching all rides from Firebase, including those rejected.
  After Fix:
    Now, it checks rejectedRides before adding a ride.
    If a ride is in rejectedRides, it is skipped.
Updated \_rejectRide() Method
Added rejected rides to rejectedRides list before updating Firebase.
Ensured immediate UI update by calling setState() after rejection.

Updated \_refreshData()
Preserved the rejectedRides list even after refreshing to ensure rejected rides do not reappear.

Code Optimization & Enhancements
Better Logging for Debugging
Added log(" Skipping previously rejected ride: \$rideId");
Improved readability and tracking of rejections.
Ensured Unique Ride IDs Before Adding
Before adding a new ride request, checked if it already exists to avoid duplicates.
Improved Firebase Update Logic
Used .update() instead of .set() to ensure only the rejected driver list is updated.

Reporting on 4th April 2025
Outstation Booking Page Development:
1. Took reference from the PreBookingPage implementation.
2. Integrated Google Maps for location tracking.
3. Implemented Firebase Database connection for storing and fetching outstation ride requests.
4. Ensured real-time updates for new ride requests.
Driver Functionality Enhancements:
1. Enabled drivers to accept or reject rides.
2. Implemented a rejection tracking system to avoid showing rejected rides again.
3. Ensured smooth UI/UX with a refresh button and real-time ride updates.
Bug Fixes & Optimizations:
1. Fixed location permission handling to improve accuracy.
2. Improved Firebase data fetching efficiency.
3. Enhanced UI components for better readability and usability.

Reporting on 5th April 2025
1. Worked on The pre-booking and outstation ride pages.
2. Modified the ride confirmation page for fetching real time ride data, with correct estimated fare, date, time, and ride type.

Daily Report
Date: 6th April 2025
Tasks Done:
Fixed issue where rejected ride requests reappeared after refresh by maintaining a local rejectedRides list.
Updated ride request listener to filter out rejected rides from UI.
Improved code readability and debug logging.
Challenges:
Synchronizing rejected rides across multiple app instances.

Reporting on 7th April 2025
1. Implemented the code to generate OTP for ride confirmation and verification.
2. Successfully storing the OTP in real time database from rider's side for its exchange between rider app and driver app, also, able to fetch the OTP at driver's end.
3. Worked on OTP verification for successful ride confirmation. This part is yet to be completed.
4. Updated the UI for OTP page to make it more clean and interactive.
The work on OTP verification part is under development and will be completed asap!

Reporting on 8th April 2025
1. Implemented the \_acceptRide() function to:
   Fetch ride request data (otp, pickup, destination) from Firebase Realtime Database.
   Update ride status to "accepted".
   Remove accepted ride from local list.
   Navigate to RideTrackingScreen.
2. Integrated RideController:
   Ensured controller is properly registered using Get.put() or Get.find().
   Stored selected ride details (rideId, otp) in controller for app-wide access.
3. Used fetched OTP for verification:
   Passed the fetched OTP to the OTP verification logic to ensure secure ride confirmation.

Daily Report 11th April 2025
1. Worked on Payment Summary Page:
Focused on improving the user interface and usability of the Payment Summary screen.
2. UI Realignment of Ride Summary:
Aligned key ride details (`Pickup`, `Destination`, `Distance`, `Duration`, `Fare`) in a structured, clean layout.
3. Improved User Interaction:
Retained the blurred background effect for modern design.
Optimized the layout of payment options and confirmation button for clarity.
4.Testing Done:
Validated scrolling behavior and sheet drag responsiveness.
Confirmed navigation to respective payment pages.

Daily Report
Date: 15th April 2025
Tasks Done:
Added profile editing features allowing users to update profile images and details.
Connected frontend and backend APIs for profile data submission.
Backend updated to accept and store images properly.
Challenges:
Handling large image uploads without performance degradation.

Reporting on 16th April 2025
1. Created Daily Earning Screen for showing driver's total earnings and ride count.
2. It also shows payment history, and navigates to DriverScreen when clicked on Home button.
3. Worked on modifying the functionalities for handling pre-booking rides.

Reporting on 18th April 2025
1. Connected the driver app to the backend and database on live server.
2. Debugged the issues occurred at backend while logging in to the app.
3. Modified the App\_document.php (live server) to make it able to store, update, and fetch the driver data.
4. Also debugged App\_documents.php as it caused errors while fetching driver data.

Reporting on 19th April 2025
Connected the User App to the Live Server Backend and Database:
1. Successfully integrated the user application with the backend API and MySQL database hosted on the live server.
2. Ensured all necessary environment configurations and URLs were correctly updated in the app for seamless communication with the server.
3. Verified that the connection is stable and data is being transmitted securely between the app and the server.
Debugged and Fixed User Login & Registration Functionality:
1. Identified and resolved issues in the backend PHP code related to user authentication and data validation.
2. Fixed input handling, error responses, and database queries to ensure accurate user registration and login processes.
3. Thoroughly tested the updated functionalities through the app—users can now register new accounts and log in without any errors.
4. Added appropriate success and error messages for better user feedback during the authentication process.

Reporting on 25th April 2025
1. Login Condition for Ride Booking:
   Verified and fixed login conditions for both Web (fetches user\_id from session) and App (fetches user\_id from GET request).
2. Ternary Operator:
   Used the ternary operator to check for user\_id in the session or GET request:
   php
   \$user\_id = isset($\_SESSION\['user\_id']) ? floatval($\_SESSION\['user\_id']) : (isset($\_GET\['user\_id']) ? floatval($\_GET\['user\_id']) : 0);
3. Session Check for Authentication:
   Implemented a check to redirect users to the login page if no valid user\_id is found.
4. Fare Estimation Issue Fixed:
   Resolved the issue of the user\_id being null, which was causing problems with the fare estimation. The user\_id is now correctly fetched, and the fare estimation is showing properly.
5. Final Outcome:
   The booking flow is now functional for both web and app bookings, users are properly redirected based on their login status, and fare estimation is now working as expected.

Daily Report
Date: 26th April 2025
Tasks Done:
Built UI for RideTrackingScreen to display ongoing ride details.
Studied methods to find nearby drivers and send ride requests selectively.
Tested navigation from ride acceptance to ride tracking.
Challenges:
Implementing efficient driver selection logic.

Daily Report
Date: 28th April 2025
Tasks Done:
Finalized RideTrackingScreen UI and integrated GPS tracking updates.
Improved real-time location update frequency for driver location.
Enhanced ride status transitions in UI.
Challenges:
Battery consumption optimization for real-time tracking.

Daily Report
Date: 29th April 2025
Tasks Done:
Improved user profile edit page with dynamic fields.
Backend APIs updated to handle image and data update requests seamlessly.
Tested complete profile update workflow.
Challenges:
Large image uploads impacting API response time

Daily Report
Date: 6th May 2025
Tasks Done:
Added offline caching for ride data to improve app responsiveness.
Implemented retry logic for failed API calls.
Improved error messages shown to user.

Date: 10th May 2025
Tasks Done:
Improved UI responsiveness and fixed minor bugs in ride request notifications.
Added new screens for driver earnings summary.
EOT;

// Create FPDF instance
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$lineHeight = 8;
$pageHeight = 270; // Bottom margin to avoid cutoff

// Split text into report blocks
$reports = explode("Daily Report", $text);
foreach ($reports as $report) {
    $report = trim($report);
    if (empty($report))
        continue;

    // Prepend title if it's not the first chunk
    $fullReport = "Daily Report\n" . $report;

    // Calculate number of lines this report will take
    $numLines = substr_count($fullReport, "\n") + 1;
    $requiredHeight = $numLines * $lineHeight;

    // If not enough space on current page, add new page
    if ($pdf->GetY() + $requiredHeight > $pageHeight) {
        $pdf->AddPage();
    }

    // Output the report
    $pdf->MultiCell(0, $lineHeight, $fullReport);
    $pdf->Ln(4); // Add spacing between reports
}

// Output PDF to file or browser
$pdf->Output('F', 'Daily_Report_Feb_May_2025.pdf');

echo "PDF generated successfully!";
?>