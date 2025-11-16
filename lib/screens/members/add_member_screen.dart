import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:path/path.dart' as path;

class AddMemberScreen extends StatefulWidget {
  const AddMemberScreen({Key? key}) : super(key: key);

  @override
  State<AddMemberScreen> createState() => _AddMemberScreenState();
}

class _AddMemberScreenState extends State<AddMemberScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _roleController = TextEditingController();
  final _branchController = TextEditingController();
  final _academicYearController = TextEditingController();
  final _skillsController = TextEditingController();
  String _selectedYear = '';
  File? _selectedImage;
  bool _isSubmitting = false;

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile =
        await picker.pickImage(source: ImageSource.gallery, imageQuality: 70);

    if (pickedFile != null) {
      setState(() => _selectedImage = File(pickedFile.path));
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedYear.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Please select a year")),
      );
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse(
            'http://10.210.246.254/Aces-flutter-api/admin/members/add_member.php'),
      );

      request.fields['name'] = _nameController.text.trim();
      request.fields['role'] = _roleController.text.trim();
      request.fields['year'] = _selectedYear;
      request.fields['branch'] = _branchController.text.trim();
      request.fields['academic_year'] = _academicYearController.text.trim();
      request.fields['skills'] = _skillsController.text.trim();

      if (_selectedImage != null) {
        request.files.add(await http.MultipartFile.fromPath(
          'photo',
          _selectedImage!.path,
          filename: path.basename(_selectedImage!.path),
        ));
      }

      var response = await request.send();
      final res = await http.Response.fromStream(response);

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("✅ Member added successfully!")),
        );
        _formKey.currentState?.reset();
        setState(() {
          _selectedImage = null;
          _selectedYear = '';
        });

        // Delay slightly to let the user see the SnackBar before navigation
        Future.delayed(const Duration(seconds: 1), () {
          Navigator.pop(context); // Go back to dashboard
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("❌ Failed: ${res.body}")),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e")),
      );
    } finally {
      setState(() => _isSubmitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text("Add Committee Member"),
        backgroundColor: Colors.deepPurple,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              // Name
              _buildTextField("Name", _nameController),
              const SizedBox(height: 12),
              // Role
              _buildTextField("Role", _roleController),
              const SizedBox(height: 12),
              // Year dropdown
              DropdownButtonFormField<String>(
                dropdownColor: Colors.grey[900],
                style: const TextStyle(color: Colors.white),
                decoration: _inputDecoration("Year"),
                value: _selectedYear.isEmpty ? null : _selectedYear,
                items: ['FE', 'SE', 'TE', 'BE']
                    .map((year) => DropdownMenuItem(
                          value: year,
                          child: Text(year),
                        ))
                    .toList(),
                onChanged: (value) {
                  setState(() => _selectedYear = value!);
                },
                validator: (value) =>
                    value == null ? 'Please select a year' : null,
              ),
              const SizedBox(height: 12),
              // Branch
              _buildTextField("Branch", _branchController),
              const SizedBox(height: 12),
              // Academic Year
              _buildTextField(
                  "Academic Year (e.g. 2024-25)", _academicYearController),
              const SizedBox(height: 12),
              // Skills
              _buildTextField("Skills", _skillsController, maxLines: 3),
              const SizedBox(height: 12),
              // Photo picker
              Row(
                children: [
                  ElevatedButton.icon(
                    onPressed: _pickImage,
                    icon: const Icon(Icons.image),
                    label: const Text("Select Photo"),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      _selectedImage != null
                          ? path.basename(_selectedImage!.path)
                          : "No image selected",
                      style: const TextStyle(color: Colors.white70),
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              // Submit
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _isSubmitting ? null : _submitForm,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.deepPurple,
                  ),
                  child: _isSubmitting
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          "Add Member",
                          style: TextStyle(color: Colors.white),
                        ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

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

  Widget _buildTextField(String label, TextEditingController controller,
      {int maxLines = 1}) {
    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      style: const TextStyle(color: Colors.white),
      decoration: _inputDecoration(label),
      validator: (value) =>
          value == null || value.isEmpty ? '$label is required' : null,
    );
  }
}
