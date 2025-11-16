import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class SetBudgetPage extends StatefulWidget {
  const SetBudgetPage({super.key});

  @override
  _SetBudgetPageState createState() => _SetBudgetPageState();
}

class _SetBudgetPageState extends State<SetBudgetPage> {
  List<dynamic> _events = [];
  int? _selectedEventId;
  String? _selectedEventName;
  final _budgetController = TextEditingController();
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    fetchEvents();
  }

  // Fetch events from API
  Future<void> fetchEvents() async {
    final response = await http.get(Uri.parse(
        'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['success'] == true) {
        setState(() {
          _events = data['event']; // Correct field based on API response
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('No events available')),
        );
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to fetch events')),
      );
    }
  }

  // Submit budget to the API
  Future<void> submitBudget() async {
    if (_selectedEventId == null || _budgetController.text.isEmpty) return;

    final double? budget = double.tryParse(_budgetController.text);
    if (budget == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Please enter a valid budget')),
      );
      return;
    }

    setState(() => _isLoading = true);

    final response = await http.post(
      Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/budget/set_budget.php'), // Update with the correct URL
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        "event_id": _selectedEventId,
        "budget_amount": budget,
      }),
    );

    setState(() => _isLoading = false);

    final result = json.decode(response.body);
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(result['message'])),
    );

    if (result['success']) {
      await Future.delayed(Duration(seconds: 3));
      Navigator.pop(context); // Close the current screen
    }
  }

  // Show event picker using a bottom sheet
  void showEventPicker() {
    showModalBottomSheet(
      context: context,
      builder: (_) => ListView(
        children: _events.map((e) {
          return ListTile(
            title: Text(e['title']),
            onTap: () {
              setState(() {
                _selectedEventId = int.tryParse(e['id'].toString());
                _selectedEventName = e['title'];
              });
              Navigator.pop(context);
            },
          );
        }).toList(),
      ),
    );
  }

  // Input decoration style for form fields
  InputDecoration _inputDecoration(String label) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: Colors.white70),
      filled: true,
      fillColor: Colors.white10,
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
      enabledBorder: OutlineInputBorder(
        borderSide: const BorderSide(color: Colors.deepPurpleAccent),
        borderRadius: BorderRadius.circular(10),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text("Set Event Budget"),
        backgroundColor: Colors.deepPurple,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            // Event picker button
            ElevatedButton.icon(
              onPressed: showEventPicker,
              icon: const Icon(Icons.event),
              label: Text(
                _selectedEventName ?? "Select Event",
                style: const TextStyle(color: Colors.white),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.deepPurpleAccent,
              ),
            ),
            const SizedBox(height: 16),

            // Budget input field
            TextField(
              controller: _budgetController,
              keyboardType: TextInputType.numberWithOptions(decimal: true),
              style: const TextStyle(color: Colors.white),
              decoration: _inputDecoration("Budget Amount (₹)"),
            ),
            const SizedBox(height: 24),

            // Submit button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isLoading ? null : submitBudget,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.deepPurple,
                ),
                child: _isLoading
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text(
                        "Submit Budget",
                        style: TextStyle(color: Colors.white),
                      ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
