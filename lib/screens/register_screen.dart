// import 'dart:convert';
// import 'package:flutter/material.dart';
// import 'package:http/http.dart' as http;
// import 'package:shared_preferences/shared_preferences.dart';
// import 'admin_login_screen.dart';

// class RegisterScreen extends StatefulWidget {
//   const RegisterScreen({super.key});

//   @override
//   State<RegisterScreen> createState() => _RegisterScreenState();
// }

// class _RegisterScreenState extends State<RegisterScreen> {
//   final TextEditingController _nameController = TextEditingController();
//   final TextEditingController _emailController = TextEditingController();
//   final TextEditingController _passwordController = TextEditingController();

//   bool _isLoading = false;
//   String? _errorMessage;
//   String? _successMessage;

//   Future<void> _registerUser() async {
//     setState(() {
//       _isLoading = true;
//       _errorMessage = null;
//       _successMessage = null;
//     });

//     const String url =
//         "http://10.210.246.254/Aces-flutter-api/admin_register.php";

//     try {
//       final response = await http.post(
//         Uri.parse(url),
//         body: {
//           'name': _nameController.text,
//           'email': _emailController.text,
//           'password': _passwordController.text,
//         },
//       );

//       final data = json.decode(response.body);

//       if (data['status'] == 'success') {
//         setState(() {
//           _successMessage = data['message'];
//         });

//         // ✅ Save is_registered = true
//         final prefs = await SharedPreferences.getInstance();
//         await prefs.setBool('is_registered', true);

//         Future.delayed(const Duration(seconds: 2), () {
//           Navigator.pushReplacement(
//             context,
//             MaterialPageRoute(
//               builder: (context) => const AdminLoginScreen(),
//             ),
//           );
//         });
//       } else {
//         setState(() {
//           _errorMessage = data['message'];
//         });
//       }
//     } catch (e) {
//       setState(() {
//         _errorMessage = "Failed to connect to server.";
//       });
//     }

//     setState(() {
//       _isLoading = false;
//     });
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Theme(
//       data: ThemeData.dark(),
//       child: Scaffold(
//         appBar: AppBar(
//           title: const Text('Register'),
//           backgroundColor: Colors.blueAccent,
//         ),
//         body: SingleChildScrollView(
//           child: Padding(
//             padding: const EdgeInsets.all(20.0),
//             child: Column(
//               children: [
//                 const SizedBox(height: 20),
//                 TextField(
//                   controller: _nameController,
//                   decoration: const InputDecoration(
//                     labelText: 'Name',
//                     filled: true,
//                     fillColor: Colors.white,
//                     border: OutlineInputBorder(),
//                     contentPadding:
//                         EdgeInsets.symmetric(vertical: 10, horizontal: 15),
//                   ),
//                 ),
//                 const SizedBox(height: 20),
//                 TextField(
//                   controller: _emailController,
//                   decoration: const InputDecoration(
//                     labelText: 'Email',
//                     filled: true,
//                     fillColor: Colors.white,
//                     border: OutlineInputBorder(),
//                     contentPadding:
//                         EdgeInsets.symmetric(vertical: 10, horizontal: 15),
//                   ),
//                 ),
//                 const SizedBox(height: 20),
//                 TextField(
//                   controller: _passwordController,
//                   obscureText: true,
//                   decoration: const InputDecoration(
//                     labelText: 'Password',
//                     filled: true,
//                     fillColor: Colors.white,
//                     border: OutlineInputBorder(),
//                     contentPadding:
//                         EdgeInsets.symmetric(vertical: 10, horizontal: 15),
//                   ),
//                 ),
//                 const SizedBox(height: 20),
//                 if (_errorMessage != null)
//                   Text(_errorMessage!,
//                       style: const TextStyle(color: Colors.red)),
//                 if (_successMessage != null)
//                   Text(_successMessage!,
//                       style: const TextStyle(color: Colors.green)),
//                 const SizedBox(height: 20),
//                 ElevatedButton(
//                   onPressed: _isLoading ? null : _registerUser,
//                   style: ElevatedButton.styleFrom(
//                     minimumSize: const Size(double.infinity, 50),
//                     backgroundColor: Colors.blueAccent,
//                     shape: RoundedRectangleBorder(
//                       borderRadius: BorderRadius.circular(30),
//                     ),
//                   ),
//                   child: _isLoading
//                       ? const CircularProgressIndicator(color: Colors.white)
//                       : const Text(
//                           'Register',
//                           style: TextStyle(fontSize: 18, color: Colors.white),
//                         ),
//                 ),
//                 const SizedBox(height: 20),
//                 // Login Button for already registered users
//                 TextButton(
//                   onPressed: () async {
//                     // Set is_registered to true in SharedPreferences
//                     final prefs = await SharedPreferences.getInstance();
//                     await prefs.setBool(
//                         'is_registered', true); // Mark as registered

//                     // Navigate to Login Screen
//                     Navigator.pushReplacement(
//                       context,
//                       MaterialPageRoute(
//                           builder: (context) => const AdminLoginScreen()),
//                     );
//                   },
//                   child: const Text(
//                     'Already Registered? Login',
//                     style: TextStyle(fontSize: 16, color: Colors.white),
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

import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'admin/admin_login_screen.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  bool _isLoading = false;
  String? _errorMessage;
  String? _successMessage;

  Future<void> _registerUser() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
      _successMessage = null;
    });

    const String url =
        "http://10.210.246.254/Aces-flutter-api/admin_register.php";

    try {
      final response = await http.post(
        Uri.parse(url),
        body: {
          'name': _nameController.text,
          'email': _emailController.text,
          'password': _passwordController.text,
        },
      );

      final data = json.decode(response.body);

      if (data['status'] == 'success') {
        setState(() {
          _successMessage = data['message'];
        });

        final prefs = await SharedPreferences.getInstance();
        await prefs.setBool('is_registered', true);

        Future.delayed(const Duration(seconds: 2), () {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (context) => const AdminLoginScreen(),
            ),
          );
        });
      } else {
        setState(() {
          _errorMessage = data['message'];
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = "Failed to connect to server.";
      });
    }

    setState(() {
      _isLoading = false;
    });
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    bool obscureText = false,
  }) {
    return TextField(
      controller: controller,
      obscureText: obscureText,
      style: const TextStyle(color: Colors.white),
      decoration: InputDecoration(
        labelText: label,
        labelStyle: const TextStyle(color: Colors.white70),
        prefixIcon: Icon(icon, color: Colors.deepPurpleAccent),
        filled: true,
        fillColor: Colors.white10,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text('Register'),
        backgroundColor: Colors.deepPurple,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            const SizedBox(height: 30),
            _buildTextField(
              controller: _nameController,
              label: 'Name',
              icon: Icons.person,
            ),
            const SizedBox(height: 20),
            _buildTextField(
              controller: _emailController,
              label: 'Email',
              icon: Icons.email,
            ),
            const SizedBox(height: 20),
            _buildTextField(
              controller: _passwordController,
              label: 'Password',
              icon: Icons.lock,
              obscureText: true,
            ),
            const SizedBox(height: 20),
            if (_errorMessage != null)
              Text(
                _errorMessage!,
                style: const TextStyle(color: Colors.redAccent),
              ),
            if (_successMessage != null)
              Text(
                _successMessage!,
                style: const TextStyle(color: Colors.greenAccent),
              ),
            const SizedBox(height: 30),
            ElevatedButton.icon(
              onPressed: _isLoading ? null : _registerUser,
              icon: const Icon(Icons.app_registration, color: Colors.white),
              label: _isLoading
                  ? const SizedBox(
                      height: 20,
                      width: 20,
                      child: CircularProgressIndicator(
                        color: Colors.white,
                        strokeWidth: 2,
                      ),
                    )
                  : const Text(
                      'Register',
                      style: TextStyle(fontSize: 16, color: Colors.white),
                    ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.deepPurple,
                minimumSize: const Size(double.infinity, 50),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
            ),
            const SizedBox(height: 20),
            TextButton(
              onPressed: () async {
                final prefs = await SharedPreferences.getInstance();
                await prefs.setBool('is_registered', true);
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(
                      builder: (context) => const AdminLoginScreen()),
                );
              },
              child: const Text(
                'Already Registered? Login',
                style: TextStyle(color: Colors.white70),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
