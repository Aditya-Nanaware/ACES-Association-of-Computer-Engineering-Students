import 'dart:convert';
import 'package:aces/screens/event/edit_event_screen.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ManageEventsScreen extends StatefulWidget {
  const ManageEventsScreen({Key? key}) : super(key: key);

  @override
  State<ManageEventsScreen> createState() => _ManageEventsScreenState();
}

class _ManageEventsScreenState extends State<ManageEventsScreen> {
  List<dynamic> _events = [];
  bool _isLoading = true;

  // Fetch events from the API
  Future<void> fetchEvents() async {
    setState(() => _isLoading = true);
    try {
      final response = await http.get(
        Uri.parse(
            'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'),
      );

      debugPrint("Response status: ${response.statusCode}");
      debugPrint("Response body: ${response.body}");

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        if (data['success'] == true) {
          // Corrected the key to 'success'
          setState(() {
            _events = data['event']; // Use 'event' here
            _isLoading = false;
          });
        } else {
          setState(() => _isLoading = false);
          showError(data['message'] ?? 'Unknown error occurred.');
        }
      } else {
        setState(() => _isLoading = false);
        showError('Server error: ${response.statusCode}');
      }
    } catch (e) {
      setState(() => _isLoading = false);
      showError('Error: $e');
    }
  }

  // Delete an event
  Future<void> deleteEvent(int eventId) async {
    final response = await http.delete(
      Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/events/delete_event.php'),
      headers: {'Content-Type': 'application/json'},
      body: json.encode({'id': eventId}),
    );

    debugPrint("Delete response status: ${response.statusCode}");
    debugPrint("Delete response body: ${response.body}");

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['status'] == 'success') {
        setState(() {
          _events.removeWhere((event) => event['id'] == eventId);
        });
        showError('Event deleted successfully.');
      } else {
        showError('Failed to delete event.');
      }
    } else {
      showError('Server error: ${response.statusCode}');
    }
  }

  // Show error message
  void showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  @override
  void initState() {
    super.initState();
    fetchEvents(); // Fetch events when screen loads
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: Colors.black,
        appBar: AppBar(
          title: const Text('Manage Events'),
          backgroundColor: Colors.deepPurple,
        ),
        body: _isLoading
            ? const Center(
                child: CircularProgressIndicator(color: Colors.deepPurple))
            : _events.isEmpty
                ? const Center(
                    child: Text('No events found.',
                        style: TextStyle(color: Colors.white70)),
                  )
                : RefreshIndicator(
                    onRefresh: fetchEvents,
                    child: ListView.builder(
                      padding: const EdgeInsets.all(20),
                      itemCount: _events.length,
                      itemBuilder: (context, index) {
                        final event = _events[index];
                        return Container(
                          margin: const EdgeInsets.only(bottom: 20),
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            color: Colors.white10,
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(
                                color: Colors.deepPurple.withOpacity(0.5)),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              // Event Image
                              buildEventImage(event['image']),
                              // Event Title
                              Text(
                                event['title'],
                                style: const TextStyle(
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.white,
                                ),
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),

                              const SizedBox(height: 8),
                              // Event Date
                              Row(
                                children: [
                                  const Icon(Icons.calendar_today,
                                      color: Colors.deepPurpleAccent, size: 18),
                                  const SizedBox(width: 6),
                                  Text("Date: ${event['event_date']}",
                                      style: const TextStyle(
                                          color: Colors.white70)),
                                ],
                              ),
                              const SizedBox(height: 6),
                              // Event Location
                              Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Icon(Icons.location_on,
                                      color: Colors.deepPurpleAccent, size: 18),
                                  const SizedBox(width: 6),
                                  Expanded(
                                    child: Text(
                                      "Location: ${event['location']}",
                                      style: const TextStyle(
                                          color: Colors.white70),
                                      softWrap: true,
                                      overflow: TextOverflow.visible,
                                    ),
                                  ),
                                ],
                              ),

                              const SizedBox(height: 10),
                              // Event Description
                              Text(
                                event['description'],
                                style: const TextStyle(color: Colors.white),
                                softWrap: true,
                                overflow: TextOverflow.visible,
                              ),

                              const Divider(color: Colors.white24, height: 24),
                              // Action Buttons
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  _actionButton(
                                      Icons.edit, "Edit", Colors.orange, () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                          builder: (context) =>
                                              EditEventScreen(event: event)),
                                    ).then((_) {
                                      fetchEvents(); // Refresh when returning
                                    });
                                  }),
                                  _actionButton(
                                      Icons.delete, "Delete", Colors.red, () {
                                    final eventId =
                                        int.tryParse(event['id'].toString());
                                    if (eventId != null) {
                                      deleteEvent(eventId);
                                    } else {
                                      showError('Invalid event ID.');
                                    }
                                  }),
                                  _actionButton(Icons.people, "View",
                                      Colors.lightBlueAccent, () {
                                    // TODO: Navigate to participants screen
                                  }),
                                ],
                              ),
                            ],
                          ),
                        );
                      },
                    )));
  }

  Widget buildEventImage(String imageUrl) {
    // Use the image URL directly from the API response
    final fullUrl = imageUrl;

    return (imageUrl != null && imageUrl.isNotEmpty)
        ? Padding(
            padding: const EdgeInsets.symmetric(vertical: 10),
            child: Image.network(
              fullUrl,
              height: 150,
              width: double.infinity,
              fit: BoxFit.cover,
              errorBuilder: (context, error, stackTrace) {
                return const Center(
                  child: Text(
                    'Failed to load image',
                    style: TextStyle(color: Colors.white70),
                  ),
                );
              },
            ),
          )
        : Padding(
            padding: const EdgeInsets.symmetric(vertical: 10),
            child: Container(
              height: 150,
              width: double.infinity,
              color: Colors.deepPurple.withOpacity(0.2),
              child: const Center(
                child: Text(
                  'No Image Available',
                  style: TextStyle(color: Colors.white70),
                ),
              ),
            ),
          );
  }

  Widget _actionButton(
      IconData icon, String label, Color color, VoidCallback onPressed) {
    return TextButton.icon(
      onPressed: onPressed,
      icon: Icon(icon, color: color, size: 20),
      label: Text(label, style: TextStyle(color: color)),
    );
  }
}
