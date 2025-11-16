import 'dart:convert';
import 'package:aces/screens/event/budget/EditBudgetScreen.dart';
import 'package:aces/screens/event/budget/edit_expense_page.dart';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:http/http.dart' as http;

class EventBudgetSummaryPage extends StatefulWidget {
  const EventBudgetSummaryPage({super.key});

  @override
  State<EventBudgetSummaryPage> createState() => _EventBudgetSummaryPageState();
}

class _EventBudgetSummaryPageState extends State<EventBudgetSummaryPage> {
  late Future<List<Event>> _futureEvents;
  Event? _selectedEvent;
  Future<BudgetData>? _futureBudget;

  @override
  void initState() {
    super.initState();
    _futureEvents = fetchEvents();
  }

  Future<void> refreshData() async {
    final events = await fetchEvents();

    Event? matchedEvent;
    if (_selectedEvent != null) {
      try {
        matchedEvent = events.firstWhere((e) => e.id == _selectedEvent!.id);
      } catch (e) {
        // If not found, fallback to null or default
        matchedEvent = null;
      }
    }

    setState(() {
      _futureEvents = Future.value(events);
      _selectedEvent = matchedEvent;
      _futureBudget =
          matchedEvent != null ? fetchBudget(matchedEvent.id) : null;
    });
  }

  Future<List<Event>> fetchEvents() async {
    final url = Uri.parse(
      'http://10.210.246.254/Aces-flutter-api/admin/budget/event_budget_summary.php?action=get_events',
    );
    final response = await http.get(url);
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      final List eventsJson = data['events'];
      return eventsJson.map((e) => Event.fromJson(e)).toList();
    } else {
      throw Exception('Failed to load events');
    }
  }

  Future<BudgetData> fetchBudget(int eventId) async {
    final url = Uri.parse(
      'http://10.210.246.254/Aces-flutter-api/admin/budget/event_budget_summary.php?action=get_budget&event_id=$eventId',
    );
    final response = await http.get(url);
    if (response.statusCode == 200) {
      return BudgetData.fromJson(jsonDecode(response.body));
    } else {
      throw Exception('Failed to load budget data');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Theme(
      data: ThemeData.dark().copyWith(
        scaffoldBackgroundColor: Colors.black,
        cardColor: const Color(0xFF1E1E2E),
        primaryColor: Colors.deepPurple,
        textTheme:
            GoogleFonts.poppinsTextTheme(ThemeData.dark().textTheme).copyWith(
          bodyMedium: const TextStyle(color: Colors.white70),
        ),
      ),
      child: Scaffold(
        appBar: AppBar(
          title: const Text('Budget Overview'),
          backgroundColor: Colors.deepPurple,
          actions: [
            IconButton(
              icon: const Icon(Icons.refresh),
              onPressed: refreshData,
              tooltip: 'Refresh',
            ),
          ],
        ),
        body: Padding(
          padding: const EdgeInsets.all(16.0),
          child: FutureBuilder<List<Event>>(
            future: _futureEvents,
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const Center(
                    child: CircularProgressIndicator(color: Colors.deepPurple));
              } else if (snapshot.hasError) {
                return Center(child: Text('Error: ${snapshot.error}'));
              } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                return const Center(child: Text('No events found'));
              }

              final events = snapshot.data!;
              return Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Select Event:',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 10),
                  DropdownButton<Event>(
                    isExpanded: true,
                    dropdownColor: const Color(0xFF2A2A40),
                    value: _selectedEvent,
                    hint: const Text('Choose an event',
                        style: TextStyle(color: Colors.white70)),
                    iconEnabledColor: Colors.deepPurpleAccent,
                    style: const TextStyle(color: Colors.white),
                    items: events.map((event) {
                      return DropdownMenuItem<Event>(
                        value: event,
                        child: Text(event.title,
                            style: const TextStyle(fontSize: 16)),
                      );
                    }).toList(),
                    onChanged: (event) {
                      setState(() {
                        _selectedEvent = event;
                        _futureBudget =
                            event != null ? fetchBudget(event.id) : null;
                      });
                    },
                  ),
                  const SizedBox(height: 20),
                  Expanded(
                    child: _futureBudget == null
                        ? const Center(
                            child:
                                Text('Select an event to see budget details'))
                        : FutureBuilder<BudgetData>(
                            future: _futureBudget,
                            builder: (context, snapshot) {
                              if (snapshot.connectionState ==
                                  ConnectionState.waiting) {
                                return const Center(
                                    child: CircularProgressIndicator(
                                        color: Colors.deepPurple));
                              } else if (snapshot.hasError) {
                                return Center(
                                    child: Text('Error: ${snapshot.error}'));
                              } else if (!snapshot.hasData) {
                                return const Center(
                                    child: Text('No budget data'));
                              }

                              final budget = snapshot.data!;
                              return ListView(
                                children: [
                                  Card(
                                    elevation: 4,
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(16),
                                    ),
                                    child: Padding(
                                      padding: const EdgeInsets.all(16.0),
                                      child: Column(
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            budget.eventTitle,
                                            style: const TextStyle(
                                              fontSize: 22,
                                              fontWeight: FontWeight.bold,
                                              color: Colors.white,
                                            ),
                                          ),
                                          const SizedBox(height: 12),
                                          Text(
                                              'Total Budget: ₹${budget.budgetAmount.toStringAsFixed(2)}'),
                                          Text(
                                              'Total Spent: ₹${budget.totalSpent.toStringAsFixed(2)}'),
                                          Text(
                                              'Remaining: ₹${budget.remainingAmount.toStringAsFixed(2)}'),
                                          const SizedBox(height: 16),
                                          Align(
                                            alignment: Alignment.centerRight,
                                            child: ElevatedButton.icon(
                                              style: ElevatedButton.styleFrom(
                                                backgroundColor:
                                                    Colors.deepPurple,
                                                shape: RoundedRectangleBorder(
                                                  borderRadius:
                                                      BorderRadius.circular(12),
                                                ),
                                              ),
                                              icon: const Icon(Icons.edit),
                                              label: const Text("Edit Budget"),
                                              onPressed: () async {
                                                await Navigator.push(
                                                  context,
                                                  MaterialPageRoute(
                                                    builder: (_) =>
                                                        EditBudgetScreen(
                                                      eventId:
                                                          _selectedEvent!.id,
                                                      currentBudget:
                                                          budget.budgetAmount,
                                                    ),
                                                  ),
                                                );
                                                // Refresh after edit
                                                refreshData();
                                              },
                                            ),
                                          ),
                                        ],
                                      ),
                                    ),
                                  ),
                                  const SizedBox(height: 24),
                                  const Text(
                                    'Expenses',
                                    style: TextStyle(
                                        fontSize: 20,
                                        fontWeight: FontWeight.w600),
                                  ),
                                  const Divider(color: Colors.white70),
                                  ...budget.expenses.map((expense) => Card(
                                        margin: const EdgeInsets.symmetric(
                                            vertical: 6),
                                        child: ListTile(
                                          title: Text(expense.description,
                                              style: const TextStyle(
                                                  color: Colors.white)),
                                          subtitle: Text(
                                              'Date: ${expense.expenseDate}',
                                              style: const TextStyle(
                                                  color: Colors.white60)),
                                          trailing: Row(
                                            mainAxisSize: MainAxisSize.min,
                                            children: [
                                              Text(
                                                '₹${expense.amount.toStringAsFixed(2)}',
                                                style: const TextStyle(
                                                    color: Colors.greenAccent),
                                              ),
                                              IconButton(
                                                icon: const Icon(Icons.edit,
                                                    color: Colors
                                                        .deepPurpleAccent),
                                                onPressed: () async {
                                                  final updated =
                                                      await Navigator.push<
                                                          bool>(
                                                    context,
                                                    MaterialPageRoute(
                                                      builder: (_) =>
                                                          EditExpensePage(
                                                        // eventId: budget
                                                        //     .eventId, // You may want to add eventId in BudgetData
                                                        expenseId: expense.id,
                                                        initialDescription:
                                                            expense.description,
                                                        initialAmount:
                                                            expense.amount,
                                                        initialExpenseDate:
                                                            expense.expenseDate,
                                                      ),
                                                    ),
                                                  );
                                                  if (updated == true) {
                                                    // Refresh budget data after editing
                                                    refreshData();
                                                  }
                                                },
                                              ),
                                            ],
                                          ),
                                        ),
                                      )),
                                ],
                              );
                            },
                          ),
                  )
                ],
              );
            },
          ),
        ),
      ),
    );
  }
}

// Models
class Event {
  final int id;
  final String title;

  Event({required this.id, required this.title});

  factory Event.fromJson(Map<String, dynamic> json) {
    return Event(
      id: int.parse(json['id'].toString()),
      title: json['title'],
    );
  }
}

class BudgetData {
  final String eventTitle;
  final double budgetAmount;
  final double totalSpent;
  final double remainingAmount;
  final List<Expense> expenses;

  BudgetData({
    required this.eventTitle,
    required this.budgetAmount,
    required this.totalSpent,
    required this.remainingAmount,
    required this.expenses,
  });

  factory BudgetData.fromJson(Map<String, dynamic> json) {
    return BudgetData(
      eventTitle: json['event_title'],
      budgetAmount: double.parse(json['budget_amount']),
      totalSpent: (json['total_spent'] as num).toDouble(),
      remainingAmount: (json['remaining_amount'] as num).toDouble(),
      expenses:
          (json['expenses'] as List).map((e) => Expense.fromJson(e)).toList(),
    );
  }
}

class Expense {
  final int id;
  final String description;
  final double amount;
  final String expenseDate;

  Expense({
    required this.id,
    required this.description,
    required this.amount,
    required this.expenseDate,
  });

  factory Expense.fromJson(Map<String, dynamic> json) {
    return Expense(
      id: int.parse(json['id'].toString()),
      description: json['description'],
      amount: double.parse(json['amount'].toString()),
      expenseDate: json['expense_date'],
    );
  }
}
