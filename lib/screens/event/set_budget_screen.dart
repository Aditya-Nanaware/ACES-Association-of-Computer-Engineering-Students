import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class SetBudgetScreen extends StatefulWidget {
  const SetBudgetScreen({super.key});

  @override
  _SetBudgetScreenState createState() => _SetBudgetScreenState();
}

class _SetBudgetScreenState extends State<SetBudgetScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _budgetController = TextEditingController();
  String _selectedEvent = '';
  String _responseMessage = '';
  bool _isLoading = false;

  // Replace with the URL of your API
  final String _apiUrl =
      'http://10.210.246.254/Aces-flutter-api/admin/events/set_budget.php';
  Future<List<Map<String, String>>> _fetchEvents() async {
    final response = await http.get(
      Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'), // replace with your local IP
    );

    if (response.statusCode == 200) {
      List<dynamic> data = json.decode(response.body);
      return data
          .map<Map<String, String>>((event) => {
                'id': event['id'].toString(),
                'title': event['title'].toString(),
              })
          .toList();
    } else {
      throw Exception('Failed to load events');
    }
  }

  // Submit the budget
  Future<void> _submitBudget() async {
    if (_formKey.currentState?.validate() ?? false) {
      setState(() {
        _isLoading = true;
      });

      final response = await http.post(
        Uri.parse(_apiUrl),
        body: {
          'event_id': _selectedEvent,
          'budget_amount': _budgetController.text,
        },
      );

      final responseData = json.decode(response.body);

      setState(() {
        _isLoading = false;
        _responseMessage = responseData['message'];
      });

      if (responseData['success']) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(_responseMessage)),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(_responseMessage)),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Set Budget for Event'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            FutureBuilder<List<Map<String, String>>>(
              future: _fetchEvents(),
              builder: (ctx, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const CircularProgressIndicator();
                } else if (snapshot.hasError) {
                  return const Text('Failed to load events');
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Text('No events available');
                }

                return DropdownButtonFormField<String>(
                  decoration: const InputDecoration(labelText: 'Select Event'),
                  value: _selectedEvent.isEmpty ? null : _selectedEvent,
                  items: snapshot.data!
                      .map((event) => DropdownMenuItem<String>(
                            value: event['id'],
                            child: Text(event['title']!),
                          ))
                      .toList(),
                  onChanged: (value) {
                    setState(() {
                      _selectedEvent = value!;
                    });
                  },
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Please select an event';
                    }
                    return null;
                  },
                );
              },
            ),
            TextFormField(
              controller: _budgetController,
              decoration: const InputDecoration(labelText: 'Budget Amount (₹)'),
              keyboardType:
                  const TextInputType.numberWithOptions(decimal: true),
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Please enter a budget amount';
                }
                return null;
              },
            ),
            const SizedBox(height: 20),
            if (_isLoading) const CircularProgressIndicator(),
            if (!_isLoading)
              ElevatedButton(
                onPressed: _submitBudget,
                child: const Text('Set Budget'),
              ),
            if (_responseMessage.isNotEmpty) ...[
              const SizedBox(height: 20),
              Text(_responseMessage, style: const TextStyle(color: Colors.red)),
            ],
          ],
        ),
      ),
    );
  }
}
