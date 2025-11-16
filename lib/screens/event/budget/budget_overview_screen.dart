import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class BudgetOverviewScreen extends StatefulWidget {
  const BudgetOverviewScreen({super.key});

  @override
  State<BudgetOverviewScreen> createState() => _BudgetOverviewScreenState();
}

class _BudgetOverviewScreenState extends State<BudgetOverviewScreen> {
  bool isLoading = true;
  String? error;
  List<dynamic> overview = [];
  Map<String, dynamic> totals = {};

  @override
  void initState() {
    super.initState();
    fetchBudgetOverview();
  }

  Future<void> fetchBudgetOverview() async {
    final url = Uri.parse(
        'http://10.210.246.254/Aces-flutter-api/admin/budget/get_budget_overview.php');

    try {
      final response = await http.get(url);
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success'] == true) {
          setState(() {
            overview = data['overview'];
            totals = data['totals'];
            isLoading = false;
          });
        } else {
          setState(() {
            error = 'Error: ${data['message']}';
            isLoading = false;
          });
        }
      } else {
        setState(() {
          error = 'Failed to fetch data';
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        error = 'Exception: $e';
        isLoading = false;
      });
    }
  }

  Widget buildBudgetCard(Map<String, dynamic> item) {
    return Container(
      margin: const EdgeInsets.symmetric(vertical: 10),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1F1F1F), Color(0xFF2A2A2A)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(15),
        boxShadow: [
          BoxShadow(
            color: Colors.deepPurpleAccent.withOpacity(0.2),
            blurRadius: 8,
            offset: const Offset(2, 4),
          ),
        ],
      ),
      child: ListTile(
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        title: Text(
          item['title'],
          style: const TextStyle(
            color: Colors.white,
            fontSize: 18,
            fontWeight: FontWeight.w600,
          ),
        ),
        subtitle: Padding(
          padding: const EdgeInsets.only(top: 8.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _budgetRow(Icons.account_balance_wallet, "Budget",
                  item['budget_amount'], Colors.blueAccent),
              _budgetRow(Icons.money_off, "Spent", item['total_spent'],
                  Colors.redAccent),
              _budgetRow(Icons.savings, "Remaining", item['remaining'],
                  Colors.greenAccent),
            ],
          ),
        ),
      ),
    );
  }

  Widget _budgetRow(IconData icon, String label, dynamic value, Color color) {
    return Row(
      children: [
        Icon(icon, color: color, size: 20),
        const SizedBox(width: 8),
        Text(
          "$label: ₹$value",
          style: TextStyle(
            color: color,
            fontSize: 14,
          ),
        ),
      ],
    );
  }

  Widget buildTotalsCard() {
    return Container(
      margin: const EdgeInsets.only(top: 20),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.deepPurpleAccent.withOpacity(0.2),
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: Colors.deepPurpleAccent, width: 1),
      ),
      child: Column(
        children: [
          const Text(
            "GRAND TOTAL",
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: Colors.deepPurpleAccent,
            ),
          ),
          const SizedBox(height: 10),
          _totalRow("Total Budget", totals['total_budget'], Colors.blueAccent),
          _totalRow("Total Spent", totals['total_spent'], Colors.redAccent),
          _totalRow(
              "Total Remaining", totals['total_remaining'], Colors.greenAccent),
        ],
      ),
    );
  }

  Widget _totalRow(String label, dynamic value, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Expanded(
            child: Text(label,
                style: TextStyle(color: color, fontWeight: FontWeight.w500)),
          ),
          Text("₹$value",
              style: TextStyle(color: color, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text('Budget Overview'),
        backgroundColor: Colors.deepPurple,
        centerTitle: true,
        elevation: 0,
      ),
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(color: Colors.deepPurpleAccent))
          : error != null
              ? Center(
                  child:
                      Text(error!, style: const TextStyle(color: Colors.red)))
              : RefreshIndicator(
                  color: Colors.deepPurple,
                  onRefresh: fetchBudgetOverview,
                  child: SingleChildScrollView(
                    physics: const AlwaysScrollableScrollPhysics(),
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      children: [
                        ...overview
                            .map((item) => buildBudgetCard(item))
                            .toList(),
                        buildTotalsCard(),
                      ],
                    ),
                  ),
                ),
    );
  }
}
