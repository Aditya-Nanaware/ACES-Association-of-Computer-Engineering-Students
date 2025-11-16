import 'dart:convert';
import 'dart:io';
import 'package:aces/screens/members/EditMemberScreen.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:csv/csv.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';

class ManageMembersScreen extends StatefulWidget {
  const ManageMembersScreen({super.key});

  @override
  State<ManageMembersScreen> createState() => _ManageMembersScreenState();
}

class _ManageMembersScreenState extends State<ManageMembersScreen> {
  List members = [];
  bool isLoading = true;

  final String baseUrl = 'http://10.210.246.254/Aces-flutter-api/admin/members';

  List<String> academicYears = ['2021-22', '2022-23', '2023-24', '2024-25'];
  String? selectedYear;

  @override
  void initState() {
    super.initState();
    fetchMembers();
  }

  Future<void> fetchMembers() async {
    try {
      String url = '$baseUrl/get_members.php';
      if (selectedYear != null && selectedYear!.isNotEmpty) {
        url += '?academic_year=$selectedYear';
      }

      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        setState(() {
          members = data['members'];
          isLoading = false;
        });
      } else {
        throw Exception("Failed to load members");
      }
    } catch (e) {
      setState(() => isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("❌ Error fetching members: $e")),
      );
    }
  }

  Future<void> deleteMember(String id) async {
    try {
      final response =
          await http.get(Uri.parse('$baseUrl/delete_member.php?id=$id'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        if (data['success'] == true) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text("✅ Member deleted")),
          );
          fetchMembers(); // Refresh list after deletion
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text("❌ ${data['message']}")),
          );
        }
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("❌ Failed to delete member")),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("❌ Error: $e")),
      );
    }
  }

  // Function to export members as CSV

  Future<void> exportCSV() async {
    try {
      final url = Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/members/export_members.php?type=csv');
      final response = await http.get(url);

      if (response.statusCode == 200) {
        final directory = await Directory.systemTemp.createTemp();
        final filePath = '${directory.path}/members.csv';
        final file = File(filePath);
        await file.writeAsBytes(response.bodyBytes);

        await Printing.sharePdf(
          bytes: response.bodyBytes,
          filename: 'members.csv',
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("✅ CSV exported")),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("❌ Failed to export CSV")),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("❌ Error: $e")),
      );
    }
  }

  // Function to export members as PDF
  Future<void> exportPDF() async {
    try {
      final url = Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/members/export_members.php?type=pdf');
      final response = await http.get(url);

      if (response.statusCode == 200) {
        final directory = await Directory.systemTemp.createTemp();
        final filePath = '${directory.path}/members.pdf';
        final file = File(filePath);
        await file.writeAsBytes(response.bodyBytes);

        await Printing.sharePdf(
          bytes: response.bodyBytes,
          filename: 'members.pdf',
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("✅ PDF exported")),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("❌ Failed to export PDF")),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("❌ Error: $e")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text('Manage Members'),
        backgroundColor: Colors.deepPurple,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : RefreshIndicator(
              onRefresh: fetchMembers,
              backgroundColor: Colors.deepPurple,
              color: Colors.white,
              child: Column(
                children: [
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: DropdownButton<String>(
                      dropdownColor: Colors.grey[850],
                      value: selectedYear,
                      hint: const Text(
                        'Filter by Academic Year',
                        style: TextStyle(color: Colors.white70),
                      ),
                      items: academicYears.map((year) {
                        return DropdownMenuItem<String>(
                          value: year,
                          child: Text(year,
                              style: const TextStyle(color: Colors.white)),
                        );
                      }).toList(),
                      onChanged: (value) {
                        setState(() {
                          selectedYear = value;
                          fetchMembers(); // Fetch members based on selected year
                        });
                      },
                    ),
                  ),
                  // Export buttons
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        ElevatedButton(
                          onPressed: exportCSV,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.deepPurple,
                          ),
                          child: const Text('Export as CSV',
                              style: TextStyle(color: Colors.white)),
                        ),
                        ElevatedButton(
                          onPressed: exportPDF,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.deepPurple,
                          ),
                          child: const Text('Export as PDF',
                              style: TextStyle(color: Colors.white)),
                        ),
                      ],
                    ),
                  ),
                  members.isEmpty
                      ? const Expanded(
                          child: Center(
                            child: Text(
                              "No members found",
                              style: TextStyle(color: Colors.white70),
                              textAlign: TextAlign.center,
                            ),
                          ),
                        )
                      : Expanded(
                          child: ListView.builder(
                            itemCount: members.length,
                            itemBuilder: (context, index) {
                              final member = members[index];
                              return Card(
                                color: Colors.grey[850],
                                margin: const EdgeInsets.symmetric(
                                    vertical: 6, horizontal: 12),
                                child: ListTile(
                                  leading: CircleAvatar(
                                    backgroundImage: NetworkImage(
                                      '$baseUrl/../../uploads/members/${member['photo'] ?? 'default_avatar.png'}',
                                    ),
                                    backgroundColor: Colors.grey,
                                  ),
                                  title: Text(member['name'],
                                      style:
                                          const TextStyle(color: Colors.white)),
                                  subtitle: Text(member['role'],
                                      style: const TextStyle(
                                          color: Colors.white70)),
                                  trailing: Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      IconButton(
                                        icon: const Icon(Icons.edit,
                                            color: Colors.amber),
                                        onPressed: () {
                                          Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  EditMemberScreen(
                                                      memberData: member),
                                            ),
                                          ).then((_) => fetchMembers());
                                        },
                                      ),
                                      IconButton(
                                        icon: const Icon(Icons.delete,
                                            color: Colors.redAccent),
                                        onPressed: () {
                                          showDialog(
                                            context: context,
                                            builder: (_) => AlertDialog(
                                              title:
                                                  const Text("Delete Member"),
                                              content: const Text(
                                                  "Are you sure you want to delete this member?"),
                                              actions: [
                                                TextButton(
                                                  child: const Text("Cancel"),
                                                  onPressed: () =>
                                                      Navigator.pop(context),
                                                ),
                                                ElevatedButton(
                                                  onPressed: () {
                                                    Navigator.pop(context);
                                                    deleteMember(member['id']);
                                                  },
                                                  style:
                                                      ElevatedButton.styleFrom(
                                                          backgroundColor:
                                                              Colors.red),
                                                  child: const Text("Delete"),
                                                ),
                                              ],
                                            ),
                                          );
                                        },
                                      ),
                                    ],
                                  ),
                                ),
                              );
                            },
                          ),
                        ),
                ],
              ),
            ),
    );
  }
}
