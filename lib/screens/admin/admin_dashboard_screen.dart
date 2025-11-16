// import 'package:aces/screens/members/add_member_screen.dart';
// import 'package:aces/screens/admin/admin_login_screen.dart';
// import 'package:aces/screens/event/manage_events_screen.dart';
// import 'package:aces/screens/members/manage_members_screen.dart';
// import 'package:flutter/material.dart';
// import 'package:shared_preferences/shared_preferences.dart';
// import 'admin_profile_screen.dart';
// import '../event/add_event_screen.dart';

// class AdminDashboardScreen extends StatelessWidget {
//   const AdminDashboardScreen({super.key});

//   Future<void> _logout(BuildContext context) async {
//     final prefs = await SharedPreferences.getInstance();
//     await prefs
//         .clear(); // Clear all saved preferences (user data, login info, etc.)

//     Navigator.pushReplacement(
//       context,
//       MaterialPageRoute(
//         builder: (context) => const AdminLoginScreen(),
//       ),
//     );
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       backgroundColor: Colors.black, // Dark background
//       appBar: AppBar(
//         title: const Text('Admin Dashboard'),
//         backgroundColor: Colors.deepPurple,
//         centerTitle: true,
//         elevation: 4,
//       ),
//       drawer: Drawer(
//         backgroundColor: Colors.grey[900],
//         child: ListView(
//           padding: EdgeInsets.zero,
//           children: [
//             const DrawerHeader(
//               decoration: BoxDecoration(
//                 color: Colors.deepPurple,
//               ),
//               child: Column(
//                 crossAxisAlignment: CrossAxisAlignment.start,
//                 children: [
//                   Icon(Icons.admin_panel_settings,
//                       size: 70, color: Colors.white),
//                   SizedBox(height: 10),
//                   Text(
//                     'Admin Panel',
//                     style: TextStyle(
//                       color: Colors.white,
//                       fontSize: 24,
//                     ),
//                   ),
//                 ],
//               ),
//             ),
//             ListTile(
//               leading: const Icon(Icons.person, color: Colors.white),
//               title:
//                   const Text('Profile', style: TextStyle(color: Colors.white)),
//               onTap: () {
//                 Navigator.push(
//                     context,
//                     MaterialPageRoute(
//                         builder: (context) => const AdminProfileScreen()));
//               },
//             ),
//             ListTile(
//               leading: const Icon(Icons.event, color: Colors.white),
//               title: const Text('Add Event',
//                   style: TextStyle(color: Colors.white)),
//               onTap: () {
//                 Navigator.push(context,
//                     MaterialPageRoute(builder: (context) => AddEventPage()));
//               },
//             ),
//             ListTile(
//               leading: const Icon(Icons.group_add, color: Colors.white),
//               title: const Text('Add Member',
//                   style: TextStyle(color: Colors.white)),
//               onTap: () {
//                 Navigator.push(
//                   context,
//                   MaterialPageRoute(
//                       builder: (context) => const AddMemberScreen()),
//                 );
//               },
//             ),
//             ListTile(
//               leading: const Icon(Icons.group, color: Colors.white),
//               title: const Text('Manage Members',
//                   style: TextStyle(color: Colors.white)),
//               onTap: () {
//                 Navigator.push(
//                   context,
//                   MaterialPageRoute(
//                       builder: (context) => const ManageMembersScreen()),
//                 );
//               },
//             ),
//             ListTile(
//               leading: const Icon(Icons.event_note, color: Colors.white),
//               title: const Text('Manage Events',
//                   style: TextStyle(color: Colors.white)),
//               onTap: () {
//                 Navigator.push(
//                   context,
//                   MaterialPageRoute(
//                       builder: (context) => const ManageEventsScreen()),
//                 );
//               },
//             ),
//             const Divider(color: Colors.white24),
//             ListTile(
//                 leading: const Icon(Icons.logout, color: Colors.redAccent),
//                 title: const Text('Logout',
//                     style: TextStyle(color: Colors.redAccent)),
//                 onTap: () => _logout(context)),
//           ],
//         ),
//       ),
//       body: Padding(
//         padding: const EdgeInsets.all(16.0),
//         child: Column(
//           crossAxisAlignment: CrossAxisAlignment.start,
//           children: [
//             const Text(
//               'Welcome, Admin!',
//               style: TextStyle(
//                 fontSize: 26,
//                 fontWeight: FontWeight.bold,
//                 color: Colors.white,
//               ),
//             ),
//             const SizedBox(height: 8),
//             const Text(
//               'Control and manage committee operations below:',
//               style: TextStyle(color: Colors.white70),
//             ),
//             const SizedBox(height: 20),
//             Expanded(
//               child: GridView.count(
//                 crossAxisCount: 2,
//                 mainAxisSpacing: 16,
//                 crossAxisSpacing: 16,
//                 children: [
//                   _dashboardCard(
//                     context,
//                     icon: Icons.event,
//                     label: 'Add Event',
//                     color: Colors.deepPurpleAccent,
//                     onTap: () {
//                       Navigator.push(
//                         context,
//                         MaterialPageRoute(builder: (context) => AddEventPage()),
//                       );
//                     },
//                   ),
//                   _dashboardCard(
//                     context,
//                     icon: Icons.event_note,
//                     label: 'Manage Events',
//                     color: Colors.teal,
//                     onTap: () {
//                       Navigator.push(
//                         context,
//                         MaterialPageRoute(
//                             builder: (context) => const ManageEventsScreen()),
//                       );
//                     },
//                   ),
//                   _dashboardCard(
//                     context,
//                     icon: Icons.group_add,
//                     label: 'Add Member',
//                     color: Colors.pinkAccent,
//                     onTap: () {
//                       Navigator.push(
//                         context,
//                         MaterialPageRoute(
//                             builder: (context) => const AddMemberScreen()),
//                       );
//                     },
//                   ),
//                   _dashboardCard(
//                     context,
//                     icon: Icons.group,
//                     label: 'Manage Members',
//                     color: Colors.indigo,
//                     onTap: () {
//                       Navigator.push(
//                         context,
//                         MaterialPageRoute(
//                             builder: (context) => const ManageMembersScreen()),
//                       );
//                     },
//                   ),
//                   _dashboardCard(
//                     context,
//                     icon: Icons.person,
//                     label: 'Profile',
//                     color: Colors.orangeAccent,
//                     onTap: () {
//                       Navigator.push(
//                         context,
//                         MaterialPageRoute(
//                             builder: (context) => const AdminProfileScreen()),
//                       );
//                     },
//                   ),
//                 ],
//               ),
//             ),
//           ],
//         ),
//       ),
//     );
//   }

//   Widget _dashboardCard(BuildContext context,
//       {required IconData icon,
//       required String label,
//       required Color color,
//       required VoidCallback onTap}) {
//     return GestureDetector(
//       onTap: onTap,
//       child: Container(
//         decoration: BoxDecoration(
//           color: color.withOpacity(0.9),
//           borderRadius: BorderRadius.circular(16),
//           boxShadow: [
//             BoxShadow(
//               color: color.withOpacity(0.6),
//               blurRadius: 8,
//               offset: const Offset(0, 4),
//             ),
//           ],
//         ),
//         padding: const EdgeInsets.all(20),
//         child: Column(
//           mainAxisAlignment: MainAxisAlignment.center,
//           children: [
//             Icon(icon, size: 48, color: Colors.white),
//             const SizedBox(height: 12),
//             Text(
//               label,
//               textAlign: TextAlign.center,
//               style: const TextStyle(
//                 fontSize: 18,
//                 color: Colors.white,
//                 fontWeight: FontWeight.w600,
//               ),
//             ),
//           ],
//         ),
//       ),
//     );
//   }
// }

import 'dart:convert';
import 'dart:io';

import 'package:aces/screens/event/budget/AddExpenseScreen.dart';
import 'package:aces/screens/event/budget/EditBudgetScreen.dart';
import 'package:aces/screens/event/budget/budget_overview_screen.dart';
import 'package:aces/screens/event/budget/event_budget_summary.dart';
import 'package:aces/screens/event/budget/set_budget_page.dart';
import 'package:aces/screens/members/add_member_screen.dart';
import 'package:aces/screens/admin/admin_login_screen.dart';
import 'package:aces/screens/event/manage_events_screen.dart';
import 'package:aces/screens/members/manage_members_screen.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'admin_profile_screen.dart';
import '../event/add_event_screen.dart';
import 'package:http/http.dart' as http;

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({super.key});

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  int _memberCount = 0;
  bool _isLoading = true;
  int _eventCount = 0;

  @override
  void initState() {
    super.initState();
    loadStats();
  }

  Future<void> loadStats() async {
    setState(() {
      _isLoading = true;
    });

    int memberCount = await fetchMemberCount();
    int eventCount = await fetchEventCount();

    setState(() {
      _memberCount = memberCount;
      _eventCount = eventCount;
      _isLoading = false;
    });
  }

  Future<void> _logout(BuildContext context) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.clear();

    Navigator.pushReplacement(
      context,
      MaterialPageRoute(
        builder: (context) => const AdminLoginScreen(),
      ),
    );
  }

  Future<int> fetchMemberCount() async {
    try {
      final response = await http.get(Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/members/get_members.php'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success']) {
          return data['count']; // ✅ Use the count from response
        } else {
          throw Exception('Error fetching members');
        }
      } else {
        throw Exception('Failed to fetch data from API');
      }
    } catch (e) {
      print('Error: $e');
      return 0;
    }
  }

  Future<int> fetchEventCount() async {
    try {
      final response = await http.get(Uri.parse(
          'http://10.210.246.254/Aces-flutter-api/admin/events/get_events.php'));

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        if (data['success']) {
          return data['count']; // ✅ Get count directly
        } else {
          throw Exception('Error fetching events');
        }
      } else {
        throw Exception('Failed to fetch data from API');
      }
    } catch (e) {
      print('Error: $e');
      return 0;
    }
  }

  Drawer buildAdminDrawer(BuildContext context) {
    return Drawer(
      backgroundColor: Colors.grey[900],
      child: FutureBuilder<SharedPreferences>(
        future: SharedPreferences.getInstance(),
        builder: (context, snapshot) {
          if (!snapshot.hasData) {
            return const Center(child: CircularProgressIndicator());
          }

          final prefs = snapshot.data!;
          final String name = prefs.getString('admin_name') ?? 'Admin';
          final String email =
              prefs.getString('admin_email') ?? 'admin@example.com';
          final String? imagePath = prefs.getString('admin_profile_image');

          return ListView(
            padding: EdgeInsets.zero,
            children: [
              DrawerHeader(
                decoration: const BoxDecoration(color: Colors.deepPurple),
                child: Row(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    CircleAvatar(
                      radius: 35,
                      backgroundColor: Colors.white,
                      backgroundImage:
                          (imagePath != null && imagePath.isNotEmpty)
                              ? FileImage(File(imagePath))
                              : null,
                      child: (imagePath == null || imagePath.isEmpty)
                          ? const Icon(Icons.person,
                              size: 40, color: Colors.deepPurple)
                          : null,
                    ),
                    const SizedBox(width: 15),
                    Expanded(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            name,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 18,
                              fontWeight: FontWeight.w600,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                          const SizedBox(height: 4),
                          Text(
                            email,
                            style: const TextStyle(
                              color: Colors.white70,
                              fontSize: 14,
                            ),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              _drawerTile(
                  context, Icons.person, 'Profile', const AdminProfileScreen()),
              _drawerTile(
                  context, Icons.event, 'Add Event', const AddEventPage()),
              _drawerTile(context, Icons.group_add, 'Add Member',
                  const AddMemberScreen()),
              _drawerTile(context, Icons.group, 'Manage Members',
                  const ManageMembersScreen()),
              _drawerTile(context, Icons.event_note, 'Manage Events',
                  const ManageEventsScreen()),
              _drawerTile(context, Icons.account_balance_wallet, 'Set Budget',
                  const SetBudgetPage()),
              _drawerTile(context, Icons.money_off, 'Add Expense',
                  const AddExpenseScreen()),
              _drawerTile(context, Icons.pie_chart_outline,
                  'Event Budget Summary', const EventBudgetSummaryPage()),
              _drawerTile(context, Icons.money_off, 'Committee Budget Overview',
                  const BudgetOverviewScreen()),
              const Divider(color: Colors.white24),
              ListTile(
                leading: const Icon(Icons.logout, color: Colors.redAccent),
                title: const Text('Logout',
                    style: TextStyle(color: Colors.redAccent)),
                onTap: () => _logout(context),
              ),
            ],
          );
        },
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text('Admin Dashboard'),
        backgroundColor: Colors.deepPurple,
        centerTitle: true,
        elevation: 6,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications, color: Colors.white),
            onPressed: () {
              // Handle notifications
              ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('No new notifications')));
            },
          )
        ],
      ),
      drawer: buildAdminDrawer(context),
      // drawer: Drawer(
      //   backgroundColor: Colors.grey[900],
      //   child: ListView(
      //     padding: EdgeInsets.zero,
      //     children: [
      //       const DrawerHeader(
      //         decoration: BoxDecoration(color: Colors.deepPurple),
      //         child: Column(
      //           crossAxisAlignment: CrossAxisAlignment.start,
      //           children: [
      //             CircleAvatar(
      //               radius: 30,
      //               backgroundColor: Colors.white,
      //               child:
      //                   Icon(Icons.person, size: 40, color: Colors.deepPurple),
      //             ),
      //             SizedBox(height: 10),
      //             Text(
      //               'Admin Panel',
      //               style: TextStyle(color: Colors.white, fontSize: 22),
      //             ),
      //           ],
      //         ),
      //       ),
      //       _drawerTile(
      //           context, Icons.person, 'Profile', const AdminProfileScreen()),
      //       _drawerTile(
      //           context, Icons.event, 'Add Event', const AddEventPage()),
      //       _drawerTile(context, Icons.group_add, 'Add Member',
      //           const AddMemberScreen()),
      //       _drawerTile(context, Icons.group, 'Manage Members',
      //           const ManageMembersScreen()),
      //       _drawerTile(context, Icons.event_note, 'Manage Events',
      //           const ManageEventsScreen()),
      //       _drawerTile(context, Icons.account_balance_wallet, 'Set Budget',
      //           const SetBudgetPage()),
      //       _drawerTile(context, Icons.money_off, 'Add Expense',
      //           const AddExpenseScreen()),
      //       _drawerTile(
      //         context,
      //         Icons
      //             .pie_chart_outline, // or Icons.attach_money or Icons.account_balance
      //         'Event Budget Summary',
      //         const EventBudgetSummaryPage(),
      //       ),
      //       _drawerTile(context, Icons.money_off, 'Committee Budget Overview',
      //           const BudgetOverviewScreen()),
      //       const Divider(color: Colors.white24),
      //       ListTile(
      //         leading: const Icon(Icons.logout, color: Colors.redAccent),
      //         title: const Text('Logout',
      //             style: TextStyle(color: Colors.redAccent)),
      //         onTap: () => _logout(context),
      //       ),
      //     ],
      //   ),
      // ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Card(
              color: Colors.deepPurple,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12)),
              elevation: 6,
              child: const ListTile(
                leading: Icon(Icons.admin_panel_settings,
                    color: Colors.white, size: 40),
                title: Text('Welcome, Admin!',
                    style: TextStyle(color: Colors.white, fontSize: 20)),
                subtitle: Text('Control and manage committee',
                    style: TextStyle(color: Colors.white70)),
              ),
            ),
            const SizedBox(height: 16),
            _buildStatsRow(),
            const SizedBox(height: 20),
            Expanded(
              child: GridView.count(
                crossAxisCount: 2,
                mainAxisSpacing: 16,
                crossAxisSpacing: 16,
                children: [
                  _dashboardCard(
                    context,
                    icon: Icons.event,
                    label: 'Add Event',
                    colors: [Colors.deepPurple, Colors.deepPurpleAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const AddEventPage()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.event_note,
                    label: 'Manage Events',
                    colors: [Colors.teal, Colors.tealAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const ManageEventsScreen()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.group_add,
                    label: 'Add Member',
                    colors: [Colors.pink, Colors.pinkAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const AddMemberScreen()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.group,
                    label: 'Manage Members',
                    colors: [Colors.indigo, Colors.indigoAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const ManageMembersScreen()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.account_balance_wallet,
                    label: 'Set Budget',
                    colors: [Colors.orange, Colors.deepOrangeAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const SetBudgetPage()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.money_off,
                    label: 'Add Expense',
                    colors: [Colors.blueGrey, Colors.cyanAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const AddExpenseScreen()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.pie_chart_outline,
                    label: 'Event Budget Summary',
                    colors: [Colors.green, Colors.lightGreenAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) =>
                                const EventBudgetSummaryPage()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.bar_chart,
                    label: 'Budget Overview',
                    colors: [Colors.purple, Colors.purpleAccent],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const BudgetOverviewScreen()),
                      );
                    },
                  ),
                  _dashboardCard(
                    context,
                    icon: Icons.person,
                    label: 'Profile',
                    colors: [Colors.brown, Colors.brown.shade300],
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => const AdminProfileScreen()),
                      );
                    },
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // ListTile _drawerTile(
  //     BuildContext context, IconData icon, String title, Widget page) {
  //   return ListTile(
  //     leading: Icon(icon, color: Colors.white),
  //     title: Text(title, style: const TextStyle(color: Colors.white)),
  //     onTap: () {
  //       Navigator.push(context, MaterialPageRoute(builder: (context) => page));
  //     },
  //   );
  // }

  Widget _drawerTile(
      BuildContext context, IconData icon, String title, Widget destination) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 8.0, vertical: 4.0),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(12),
        child: InkWell(
          borderRadius: BorderRadius.circular(12),
          splashColor: Colors.deepPurpleAccent.withOpacity(0.3),
          onTap: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => destination),
            );
          },
          child: Container(
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
            child: Row(
              children: [
                Icon(icon, color: Colors.deepPurpleAccent, size: 24),
                const SizedBox(width: 16),
                Expanded(
                  child: Text(
                    title,
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.w500,
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

  // Future<void> _logout(BuildContext context) async {
  //   final prefs = await SharedPreferences.getInstance();
  //   await prefs.clear();
  //   Navigator.pushReplacement(
  //     context,
  //     MaterialPageRoute(builder: (context) => const AdminLoginScreen()),
  //   );
  // }

  Widget _buildStatsRow() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        _statCard(
          _isLoading ? "..." : _memberCount.toString(),
          "Total Members",
          Icons.group,
          Colors.blue,
        ),
        _statCard(
          _isLoading ? "..." : _eventCount.toString(),
          "Total Events",
          Icons.event,
          Colors.green,
        ),
      ],
    );
  }

  Widget _statCard(String count, String title, IconData icon, Color color) {
    return Expanded(
      child: Card(
        color: color,
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: const BoxDecoration(
                  shape: BoxShape.circle,
                  color: Colors.white,
                ),
                child: Icon(icon, color: color),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      count,
                      style: const TextStyle(color: Colors.white, fontSize: 18),
                    ),
                    Text(title, style: const TextStyle(color: Colors.white70)),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _dashboardCard(
    BuildContext context, {
    required IconData icon,
    required String label,
    required List<Color> colors,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(colors: colors),
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: colors.first.withOpacity(0.6),
              blurRadius: 8,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 48, color: Colors.white),
            const SizedBox(height: 12),
            Text(
              label,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 18,
                color: Colors.white,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
