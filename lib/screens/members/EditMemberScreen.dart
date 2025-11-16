import 'dart:developer';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;
import 'package:path/path.dart' as path;
import 'dart:convert';

class EditMemberScreen extends StatefulWidget {
  final Map<String, dynamic> memberData;

  const EditMemberScreen({super.key, required this.memberData});

  @override
  State<EditMemberScreen> createState() => _EditMemberScreenState();
}

class _EditMemberScreenState extends State<EditMemberScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _nameController;
  late TextEditingController _roleController;
  late TextEditingController _yearController;
  late TextEditingController _branchController;
  late TextEditingController _skillsController;
  late TextEditingController _academicYearController;
  File? _selectedImage;
  bool _isSubmitting = false;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.memberData['name']);
    _roleController = TextEditingController(text: widget.memberData['role']);
    _yearController = TextEditingController(text: widget.memberData['year']);
    _branchController =
        TextEditingController(text: widget.memberData['branch']);
    _skillsController =
        TextEditingController(text: widget.memberData['skills']);
    _academicYearController =
        TextEditingController(text: widget.memberData['academic_year']);
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedImage =
        await picker.pickImage(source: ImageSource.gallery, imageQuality: 80);

    if (pickedImage != null) {
      setState(() => _selectedImage = File(pickedImage.path));
    }
  }

  Future<void> _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse(
            'http://10.210.246.254/Aces-flutter-api/admin/members/edit_member.php'),
      );

      request.fields['id'] = widget.memberData['id'].toString();
      request.fields['name'] = _nameController.text.trim();
      request.fields['role'] = _roleController.text.trim();
      request.fields['year'] = _yearController.text.trim();
      request.fields['branch'] = _branchController.text.trim();
      request.fields['skills'] = _skillsController.text.trim();
      request.fields['academic_year'] = _academicYearController.text.trim();

      if (_selectedImage != null) {
        request.files.add(await http.MultipartFile.fromPath(
          'image',
          _selectedImage!.path,
          filename: path.basename(_selectedImage!.path),
        ));
      }

      final response = await request.send();
      final resBody = await http.Response.fromStream(response);

      log("RAW RESPONSE: ${resBody.body}"); // Add this

      final result = json.decode(resBody.body);

      if (response.statusCode == 200 && result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('✅ Member updated successfully!')),
        );
        Navigator.pop(context, true); // Trigger refresh in previous screen
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('❌ Failed: ${result['message']}')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    } finally {
      setState(() => _isSubmitting = false);
    }
  }

  void _confirmDelete() async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Confirm Delete"),
        content: const Text("Are you sure you want to delete this member?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text("Cancel"),
          ),
          ElevatedButton(
            onPressed: () => Navigator.pop(context, true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text("Delete"),
          ),
        ],
      ),
    );

    if (confirm == true) {
      _deleteMember();
    }
  }

  Future<void> _deleteMember() async {
    setState(() => _isSubmitting = true);

    try {
      final response = await http.post(
        Uri.parse(
            'http://10.210.246.254/Aces-flutter-api/admin/members/delete_member.php'),
        body: {
          'id': widget.memberData['id'].toString(),
        },
      );

      log("DELETE RESPONSE: ${response.body}");

      final result = json.decode(response.body);

      if (response.statusCode == 200 && result['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('✅ Member deleted successfully!')),
        );
        Navigator.pop(context, true); // Trigger refresh
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('❌ Failed: ${result['message']}')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    } finally {
      setState(() => _isSubmitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF121212),
      appBar: AppBar(
        title: const Text("Edit Member"),
        backgroundColor: Colors.deepPurple,
        elevation: 0,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Center(
                child: GestureDetector(
                  onTap: _pickImage,
                  child: Stack(
                    alignment: Alignment.bottomRight,
                    children: [
                      CircleAvatar(
                        radius: 55,
                        backgroundColor: Colors.grey[800],
                        child: ClipOval(
                          child: _selectedImage != null
                              ? Image.file(
                                  _selectedImage!,
                                  width: 110,
                                  height: 110,
                                  fit: BoxFit.cover,
                                )
                              : widget.memberData['photo'] != null &&
                                      widget.memberData['photo']
                                          .toString()
                                          .isNotEmpty &&
                                      widget.memberData['photo'].toString() !=
                                          'null'
                                  ? Image.network(
                                      "http://10.210.246.254/Aces-flutter-api/uploads/members/${widget.memberData['photo']}",
                                      width: 110,
                                      height: 110,
                                      fit: BoxFit.cover,
                                      errorBuilder:
                                          (context, error, stackTrace) {
                                        return Image.asset(
                                          'assets/images/default_avatar.png',
                                          width: 110,
                                          height: 110,
                                          fit: BoxFit.cover,
                                        );
                                      },
                                    )
                                  : Image.asset(
                                      'assets/images/default_avatar.png',
                                      width: 110,
                                      height: 110,
                                      fit: BoxFit.cover,
                                    ),
                        ),
                      ),
                      const CircleAvatar(
                        radius: 18,
                        backgroundColor: Colors.white,
                        child: Icon(Icons.edit, color: Colors.black, size: 20),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),
              _buildTextField(_nameController, "Name"),
              _buildTextField(_roleController, "Role"),
              _buildTextField(_yearController, "Year"),
              _buildTextField(_branchController, "Branch"),
              _buildTextField(_skillsController, "Skills"),
              _buildTextField(_academicYearController, "Academic Year"),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: _isSubmitting ? null : _submitForm,
                  icon: const Icon(Icons.save),
                  label: Text(
                    _isSubmitting ? "Saving..." : "Save Changes",
                    style: const TextStyle(fontSize: 16),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.deepPurple,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                    elevation: 4,
                  ),
                ),
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: _isSubmitting ? null : _confirmDelete,
                  icon: const Icon(Icons.delete),
                  label: const Text(
                    "Delete Member",
                    style: TextStyle(fontSize: 16),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.redAccent,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                    elevation: 4,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTextField(TextEditingController controller, String label) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: TextFormField(
        controller: controller,
        style: const TextStyle(color: Colors.white),
        decoration: InputDecoration(
          labelText: label,
          labelStyle: const TextStyle(color: Colors.white70),
          filled: true,
          fillColor: Colors.grey[850],
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Colors.deepPurple),
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Colors.deepPurpleAccent),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Colors.deepPurple, width: 2),
          ),
        ),
        validator: (value) =>
            value == null || value.isEmpty ? 'Enter $label' : null,
      ),
    );
  }
}
