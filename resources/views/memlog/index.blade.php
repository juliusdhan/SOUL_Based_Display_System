<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Logs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="{{ asset('custom.css') }}">
</head>

<body>
    <div class="container">
        <!-- College Heading Section -->
        <div class="college-header d-flex align-items-center">
            <img src="{{ asset('college_logo.png') }}" alt="College Logo" class="college-logo" style="width: 80px; height: auto; margin-right: 15px;">

            <div style="text-align: left; margin-top: 20px;">
                <!-- <h1 style="font-size: 28px; color: orange; font-family: 'Arial', sans-serif;">কেচি দাস কমার্স কলেজ</h1> -->
                <h2 style="font-size: 24px; color: black;">K. C. Das Commerce College</h2>
                <p style="font-size: 14px; color: gray;">
                    A Provincialised College under the Govt. of Assam.<br>
                    UGC Recognized, AICTE Approved, Affiliated to Gauhati University, NAAC Accredited, ISO 9001:2015
                </p>
            </div>
        </div>

        <!-- Page Heading Section -->
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 10px; margin-bottom: 7px;"> 
    <!-- Flex container with column direction for stacking items vertically -->
    <h3 style="font-size: 32px; color: black; font-family: 'Arial', sans-serif; margin: 0;">
        Library Attendance Logs
    </h3>
    
    <p style="font-size: 20px; color: black; margin: 5px 0 0;">
       Today's Date: <span id="current-date"></span>
    </p>
</div>



        <!-- Table Section -->
        <div class="table-responsive">
    <table class="table table-striped" style="text-align: center;"> <!-- Center align the entire table -->
        <thead>
            <tr>
                <th style="text-align: center;">Member Code</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">Login Time</th>
                <th style="text-align: center;">Logout Time</th>
                <th style="text-align: center;">Location</th>
            </tr>
        </thead>
        <tbody id="logs-table">
            <!-- Dynamic log rows will be appended here -->
            @foreach ($logs as $log)
            <tr id="log-{{ $log->LogID }}">
                <td>{{ $log->mem_cd }}</td> <!-- Display Member Code -->
                <td>{{ $log->mem_firstnm }} {{ $log->mem_lstnm }}</td> <!-- Display full name -->
                <td>{{ $log->Login_time }}</td>
                <td class="logout-time">{{ $log->Logout_time ?? '-' }}</td>
                <td>{{ $log->Location }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


    <!-- JavaScript for real-time updates -->
    <script>
        let lastLogID = 0; // Initialize with 0 or the last known LogID
        const processedLogs = new Set(); // To track already processed log IDs

        function fetchNewLogs() {
            $.ajax({
                url: "{{ route('logs.fetch') }}", // Calls the named route 'logs.fetch'
                method: "GET",
                data: {
                    lastLogID: lastLogID
                },
                success: function(data) {
                    if (data.length > 0) {
                        data.forEach(log => {
                            if (processedLogs.has(log.LogID)) {
                                // If it's an existing log, check for updates
                                if (log.Logout_time) {
                                    // Update the logout time if not already displayed
                                    const logoutTimeCell = $(`#log-${log.LogID} .logout-time`);
                                    if (logoutTimeCell.text().trim() === '-') {
                                        // Update the table with the new logout time
                                        logoutTimeCell.text(log.Logout_time);

                                        // Show a thank-you popup
                                        Swal.fire({
                                            title: `Thank you, visit again ${log.mem_firstnm} ${log.mem_lstnm}!`,
                                            html: `
                                            <p><strong>Member Code:</strong> ${log.mem_cd}</p>
                                            <p><strong>Logout Time:</strong> ${log.Logout_time}</p>
                                        `,
                                            icon: 'info',
                                            timer: 1000,
                                            showConfirmButton: false
                                        });
                                    }
                                }
                                return; // Skip further processing for this log
                            }

                            // Add to processed logs
                            processedLogs.add(log.LogID);

                            // Update the table with new data
                            $('#logs-table').prepend(`
                            <tr id="log-${log.LogID}">
                                <td>${log.mem_cd}</td>
                                <td>${log.mem_firstnm} ${log.mem_lstnm}</td>
                                <td>${log.Login_time}</td>
                                <td class="logout-time">${log.Logout_time || '-'}</td>
                                <td>${log.Location}</td>
                            </tr>
                        `);

                            if (!log.Logout_time) {
                                // Show a welcome popup for new login
                                Swal.fire({
                                    title: `Welcome ${log.mem_firstnm} ${log.mem_lstnm}!`,
                                    html: `
                                    <p><strong>Member Code:</strong> ${log.mem_cd}</p>
                                    <p><strong>Login Time:</strong> ${log.Login_time}</p>
                                    <p><strong>Location:</strong> ${log.Location}</p>
                                `,
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }

                            // Update the lastLogID
                            lastLogID = Math.max(lastLogID, log.LogID);
                        });
                    }
                },
                error: function() {
                    console.error("Failed to fetch logs.");
                }
            });
        }

        // Check for new logs every second
        setInterval(fetchNewLogs, 1000);



        document.getElementById('current-date').innerText = new Date().toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });

    </script>
    






</body>

</html>