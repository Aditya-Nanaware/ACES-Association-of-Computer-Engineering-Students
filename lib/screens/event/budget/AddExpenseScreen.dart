// import 'package:flutter/material.dart';
// import 'package:http/http.dart' as http;
// import 'dart:convert';

// class AddExpenseScreen extends StatefulWidget {
//   const AddExpenseScreen({super.key});

//   @override
//   _AddExpenseScreenState createState() => _AddExpenseScreenState();
// }

// class _AddExpenseScreenState extends State<AddExpenseScreen> {
//   final _formKey = GlobalKey<FormState>();
//   int? selectedEventId;
//   String? description;
//   double? amount;
//   DateTime? expenseDate;

//   bool isLoading = false;
//   List<Map<String, dynamic>> events = [];

//   @override
//   void initState() {
//     super.initState();
//     fetchEvents();
//   }

//   Future<void> fetchEvents() async {
//     final response = await http.get(Uri.parse(
//         'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'));

//     if (response.statusCode == 200) {
//       final data = json.decode(response.body);
//       if (data['success']) {
//         setState(() {
//           events = List<Map<String, dynamic>>.from(data['event']);
//         });
//       } else {
//         ScaffoldMessenger.of(context).showSnackBar(
//           SnackBar(content: Text(data['message'] ?? 'Failed to load events')),
//         );
//       }
//     } else {
//       ScaffoldMessenger.of(context).showSnackBar(
//         SnackBar(content: Text("Error: ${response.statusCode}")),
//       );
//     }
//   }

//   Future<void> submitExpense() async {
//     if (!_formKey.currentState!.validate() ||
//         selectedEventId == null ||
//         expenseDate == null) return;

//     _formKey.currentState!.save();
//     setState(() => isLoading = true);

//     try {
//       final response = await http.post(
//         Uri.parse(
//             'http://10.210.246.254/Aces-flutter-api/admin/budget/add_expense.php'),
//         headers: {'Content-Type': 'application/json'},
//         body: jsonEncode({
//           'event_id': selectedEventId,
//           'description': description,
//           'amount': amount,
//           'expense_date': expenseDate!.toIso8601String().substring(0, 10),
//         }),
//       );
//       print('Sending: ${{
//         'event_id': selectedEventId,
//         'description': description,
//         'amount': amount,
//         'expense_date': expenseDate!.toIso8601String().substring(0, 10),
//       }}');

//       print('Response: ${response.body}');

//       final result = jsonDecode(response.body);

//       ScaffoldMessenger.of(context).showSnackBar(
//         SnackBar(content: Text(result['message'] ?? 'Unexpected error')),
//       );

//       if (result['success']) {
//         _formKey.currentState!.reset();
//         setState(() {
//           selectedEventId = null;
//           expenseDate = null;
//         });
//       }
//     } catch (e) {
//       ScaffoldMessenger.of(context).showSnackBar(
//         SnackBar(content: Text('Error: $e')),
//       );
//     } finally {
//       setState(() => isLoading = false);
//     }
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(title: Text("Add Expense")),
//       body: Padding(
//         padding: const EdgeInsets.all(16.0),
//         child: events.isEmpty
//             ? Center(child: CircularProgressIndicator())
//             : Form(
//                 key: _formKey,
//                 child: SingleChildScrollView(
//                   child: Column(
//                     children: [
//                       DropdownButtonFormField<int>(
//                         value: selectedEventId,
//                         decoration: InputDecoration(labelText: "Select Event"),
//                         items: events.map((event) {
//                           return DropdownMenuItem<int>(
//                             value: int.parse(event['id'].toString()),
//                             child: Text(event['title']),
//                           );
//                         }).toList(),
//                         onChanged: (val) =>
//                             setState(() => selectedEventId = val),
//                         validator: (val) =>
//                             val == null ? 'Please select an event' : null,
//                       ),
//                       TextFormField(
//                         decoration:
//                             InputDecoration(labelText: "Expense Description"),
//                         onSaved: (val) => description = val,
//                         validator: (val) => val == null || val.isEmpty
//                             ? 'Enter description'
//                             : null,
//                       ),
//                       TextFormField(
//                         decoration: InputDecoration(labelText: "Amount (₹)"),
//                         keyboardType:
//                             TextInputType.numberWithOptions(decimal: true),
//                         onSaved: (val) => amount = double.tryParse(val ?? ''),
//                         validator: (val) =>
//                             val == null || double.tryParse(val) == null
//                                 ? 'Enter valid amount'
//                                 : null,
//                       ),
//                       SizedBox(height: 10),
//                       ListTile(
//                         title: Text(expenseDate == null
//                             ? 'Select Date'
//                             : 'Date: ${expenseDate!.toLocal().toString().split(' ')[0]}'),
//                         trailing: Icon(Icons.calendar_today),
//                         onTap: () async {
//                           final picked = await showDatePicker(
//                             context: context,
//                             initialDate: DateTime.now(),
//                             firstDate: DateTime(2023),
//                             lastDate: DateTime(2030),
//                           );
//                           if (picked != null)
//                             setState(() => expenseDate = picked);
//                         },
//                       ),
//                       SizedBox(height: 20),
//                       isLoading
//                           ? CircularProgressIndicator()
//                           : ElevatedButton(
//                               onPressed: submitExpense,
//                               child: Text("Submit Expense"),
//                             ),
//                     ],
//                   ),
//                 ),
//               ),
//       ),
//     );
//   }
// }
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class AddExpenseScreen extends StatefulWidget {
  const AddExpenseScreen({super.key});

  @override
  _AddExpenseScreenState createState() => _AddExpenseScreenState();
}

class _AddExpenseScreenState extends State<AddExpenseScreen> {
  final _formKey = GlobalKey<FormState>();
  int? selectedEventId;
  String? description;
  double? amount;
  DateTime? expenseDate;

  bool isLoading = false;
  List<Map<String, dynamic>> events = [];

  @override
  void initState() {
    super.initState();
    fetchEvents();
  }

  Future<void> fetchEvents() async {
    final response = await http.get(Uri.parse(
        'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['success']) {
        setState(() {
          events = List<Map<String, dynamic>>.from(data['event']);
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? 'Failed to load events')),
        );
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: ${response.statusCode}")),
      );
    }
  }

  // Future<void> submitExpense() async {
  //   if (!_formKey.currentState!.validate() ||
  //       selectedEventId == null ||
  //       expenseDate == null) return;

  //   _formKey.currentState!.save();
  //   setState(() => isLoading = true);

  //   try {
  //     final response = await http.post(
  //       Uri.parse(
  //           'http://10.210.246.254/Aces-flutter-api/admin/budget/add_expense.php'),
  //       headers: {'Content-Type': 'application/json'},
  //       body: jsonEncode({
  //         'event_id': selectedEventId,
  //         'description': description,
  //         'amount': amount,
  //         'expense_date': expenseDate!.toIso8601String().substring(0, 10),
  //       }),
  //     );

  //     final result = jsonDecode(response.body);

  //     ScaffoldMessenger.of(context).showSnackBar(
  //       SnackBar(content: Text(result['message'] ?? 'Unexpected error')),
  //     );

  //     if (result['success']) {
  //       _formKey.currentState!.reset();
  //       setState(() {
  //         selectedEventId = null;
  //         expenseDate = null;
  //       });
  //     }
  //   } catch (e) {
  //     ScaffoldMessenger.of(context).showSnackBar(
  //       SnackBar(content: Text('Error: $e')),
  //     );
  //   } finally {
  //     setState(() => isLoading = false);
  //   }
  // }
  Future<void> submitExpense() async {
    if (!_formKey.currentState!.validate() ||
        selectedEventId == null ||
        expenseDate == null) return;

    _formKey.currentState!.save();
    setState(() => isLoading = true);

    try {
      final response = await http.post(
        Uri.parse(
            'http://10.210.246.254/Aces-flutter-api/admin/budget/add_expense.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'event_id': selectedEventId,
          'description': description,
          'amount': amount,
          'expense_date': expenseDate!.toIso8601String().substring(0, 10),
        }),
      );

      final result = jsonDecode(response.body);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(result['message'] ?? 'Unexpected error')),
      );

      if (result['success']) {
        _formKey.currentState!.reset();
        setState(() {
          selectedEventId = null;
          expenseDate = null;
        });

        await Future.delayed(Duration(seconds: 2));
        Navigator.pop(context); // Close the current screen after success
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    } finally {
      setState(() => isLoading = false);
    }
  }

  InputDecoration _inputDecoration(String label) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: Colors.white70),
      filled: true,
      fillColor: Colors.grey[900],
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
      focusedBorder: OutlineInputBorder(
        borderSide: const BorderSide(color: Colors.deepPurpleAccent),
        borderRadius: BorderRadius.circular(12),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text("Add Expense"),
        backgroundColor: Colors.deepPurple,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: events.isEmpty
            ? const Center(
                child:
                    CircularProgressIndicator(color: Colors.deepPurpleAccent))
            : Form(
                key: _formKey,
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      DropdownButtonFormField<int>(
                        value: selectedEventId,
                        decoration: _inputDecoration("Select Event"),
                        dropdownColor: Colors.grey[900],
                        iconEnabledColor: Colors.white,
                        items: events.map((event) {
                          return DropdownMenuItem<int>(
                            value: int.parse(event['id'].toString()),
                            child: Text(event['title'],
                                style: const TextStyle(color: Colors.white)),
                          );
                        }).toList(),
                        onChanged: (val) =>
                            setState(() => selectedEventId = val),
                        validator: (val) =>
                            val == null ? 'Please select an event' : null,
                      ),
                      const SizedBox(height: 16),
                      TextFormField(
                        decoration: _inputDecoration("Expense Description"),
                        style: const TextStyle(color: Colors.white),
                        onSaved: (val) => description = val,
                        validator: (val) => val == null || val.isEmpty
                            ? 'Enter description'
                            : null,
                      ),
                      const SizedBox(height: 16),
                      TextFormField(
                        decoration: _inputDecoration("Amount (₹)"),
                        style: const TextStyle(color: Colors.white),
                        keyboardType: const TextInputType.numberWithOptions(
                            decimal: true),
                        onSaved: (val) => amount = double.tryParse(val ?? ''),
                        validator: (val) =>
                            val == null || double.tryParse(val) == null
                                ? 'Enter valid amount'
                                : null,
                      ),
                      const SizedBox(height: 16),
                      ListTile(
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        tileColor: Colors.grey[900],
                        title: Text(
                          expenseDate == null
                              ? 'Select Date'
                              : 'Date: ${expenseDate!.toLocal().toString().split(' ')[0]}',
                          style: const TextStyle(color: Colors.white),
                        ),
                        trailing: const Icon(Icons.calendar_today,
                            color: Colors.deepPurpleAccent),
                        onTap: () async {
                          final picked = await showDatePicker(
                            context: context,
                            initialDate: DateTime.now(),
                            firstDate: DateTime(2023),
                            lastDate: DateTime(2030),
                            builder: (context, child) {
                              return Theme(
                                data: ThemeData.dark().copyWith(
                                  colorScheme: const ColorScheme.dark(
                                    primary: Colors.deepPurple,
                                    onPrimary: Colors.white,
                                    surface: Colors.grey,
                                    onSurface: Colors.white,
                                  ),
                                  dialogBackgroundColor: Colors.black,
                                ),
                                child: child!,
                              );
                            },
                          );
                          if (picked != null) {
                            setState(() => expenseDate = picked);
                          }
                        },
                      ),
                      const SizedBox(height: 24),
                      Center(
                        child: isLoading
                            ? const CircularProgressIndicator(
                                color: Colors.deepPurpleAccent)
                            : ElevatedButton.icon(
                                onPressed: submitExpense,
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.deepPurpleAccent,
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 24, vertical: 12),
                                  shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(10)),
                                ),
                                icon: const Icon(
                                  Icons.save,
                                  color: Colors.white,
                                ),
                                label: const Text(
                                  "Submit Expense",
                                  style: TextStyle(color: Colors.white),
                                ),
                              ),
                      ),
                    ],
                  ),
                ),
              ),
      ),
    );
  }
}
