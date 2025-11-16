// import 'dart:async';
// import 'package:aces/screens/admin_dashboard_screen.dart';
// import 'package:aces/screens/admin_login_screen.dart';
// import 'package:aces/screens/register_screen.dart';
// import 'package:flutter/material.dart';
// import 'package:shared_preferences/shared_preferences.dart';

// class SplashScreen extends StatefulWidget {
//   const SplashScreen({super.key});

//   @override
//   State<SplashScreen> createState() => _SplashScreenState();
// }

// class _SplashScreenState extends State<SplashScreen>
//     with SingleTickerProviderStateMixin {
//   late AnimationController _controller;
//   late Animation<double> _animation;

//   @override
//   void initState() {
//     super.initState();

//     // Animation setup
//     _controller = AnimationController(
//       vsync: this,
//       duration: const Duration(seconds: 2),
//     );
//     _animation = CurvedAnimation(parent: _controller, curve: Curves.easeInOut);

//     _controller.forward();

//     // Check registration and login status after animation starts
//     _checkRegistrationStatus();
//   }

//   Future<void> _checkRegistrationStatus() async {
//     final prefs = await SharedPreferences.getInstance();
//     final bool? isRegistered = prefs.getBool('is_registered');

//     // Log the registration status
//     print("isRegistered: $isRegistered");

//     await Future.delayed(const Duration(seconds: 3));

//     if (isRegistered == null || !isRegistered) {
//       // Navigate to Register Screen
//       Navigator.pushReplacement(
//         context,
//         MaterialPageRoute(builder: (context) => const RegisterScreen()),
//       );
//     } else {
//       final String? adminId = prefs.getString('admin_id');
//       print("adminId: $adminId");

//       if (adminId != null) {
//         // If logged in, navigate to Admin Dashboard
//         Navigator.pushReplacement(
//           context,
//           MaterialPageRoute(builder: (context) => const AdminDashboardScreen()),
//         );
//       } else {
//         // If not logged in, navigate to Admin Login screen
//         Navigator.pushReplacement(
//           context,
//           MaterialPageRoute(builder: (context) => const AdminLoginScreen()),
//         );
//       }
//     }
//   }

//   @override
//   void dispose() {
//     _controller.dispose();
//     super.dispose();
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       body: Container(
//         decoration: const BoxDecoration(
//           gradient: LinearGradient(
//             colors: [Colors.deepPurple, Colors.indigo],
//             begin: Alignment.topLeft,
//             end: Alignment.bottomRight,
//           ),
//         ),
//         child: Center(
//           child: ScaleTransition(
//             scale: _animation,
//             child: Column(
//               mainAxisSize: MainAxisSize.min,
//               children: [
//                 // Replace with your logo or a custom icon
//                 const Icon(Icons.account_circle,
//                     size: 100, color: Colors.white),
//                 const SizedBox(height: 20),
//                 const Text(
//                   'ACES Committee',
//                   style: TextStyle(
//                     color: Colors.white,
//                     fontSize: 28,
//                     fontWeight: FontWeight.bold,
//                     letterSpacing: 1.5,
//                   ),
//                 ),
//                 const SizedBox(height: 10),
//                 const Text(
//                   'Welcome to the Admin Portal',
//                   style: TextStyle(
//                     color: Colors.white70,
//                     fontSize: 18,
//                     fontWeight: FontWeight.w400,
//                   ),
//                 ),
//               ],
//             ),
//           ),
//         ),
//       ),
//     );
//   }
// }

import 'dart:async';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:aces/screens/admin/admin_dashboard_screen.dart';
import 'package:aces/screens/admin/admin_login_screen.dart';
import 'package:aces/screens/register_screen.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();

    _controller = AnimationController(
      duration: const Duration(seconds: 2),
      vsync: this,
    );

    _animation = CurvedAnimation(parent: _controller, curve: Curves.easeInOut);
    _controller.forward();

    _checkRegistrationStatus();
  }

  Future<void> _checkRegistrationStatus() async {
    final prefs = await SharedPreferences.getInstance();
    final bool? isRegistered = prefs.getBool('is_registered');

    await Future.delayed(const Duration(seconds: 3));

    if (isRegistered == null || !isRegistered) {
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (context) => const RegisterScreen()),
      );
    } else {
      final String? adminId = prefs.getString('admin_id');
      if (adminId != null) {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const AdminDashboardScreen()),
        );
      } else {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (context) => const AdminLoginScreen()),
        );
      }
    }
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [Colors.black, Colors.deepPurple],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: Center(
          child: ScaleTransition(
            scale: _animation,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Image.asset(
                  'assets/images/aces_logo.png',
                  width: 160,
                ),
                const SizedBox(height: 30),
                const Text(
                  'ACES COMMITTEE',
                  style: TextStyle(
                    color: Colors.amber,
                    fontSize: 26,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 2,
                  ),
                ),
                const SizedBox(height: 10),
                const Text(
                  'Association of Computer Engineering Students',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    color: Colors.white70,
                    fontSize: 14,
                    letterSpacing: 1,
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
