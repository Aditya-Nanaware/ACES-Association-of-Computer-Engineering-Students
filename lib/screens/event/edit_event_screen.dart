import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;

class EditEventScreen extends StatefulWidget {
  final Map<String, dynamic> event;

  const EditEventScreen({super.key, required this.event});

  @override
  State<EditEventScreen> createState() => _EditEventScreenState();
}

class _EditEventScreenState extends State<EditEventScreen> {
  late TextEditingController _titleController;
  late TextEditingController _descriptionController;
  late TextEditingController _locationController;
  late DateTime _eventDate;
  bool _isLoading = false;
  File? _selectedImage;

  @override
  void initState() {
    super.initState();
    _titleController = TextEditingController(text: widget.event['title']);
    _descriptionController =
        TextEditingController(text: widget.event['description']);
    _locationController = TextEditingController(text: widget.event['location']);
    _eventDate = DateTime.parse(widget.event['event_date']);
  }

  Future<void> _pickEventImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedFile =
        await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      setState(() {
        _selectedImage = File(pickedFile.path);
      });
    }
  }

  Future<void> _updateEvent() async {
    if (_titleController.text.trim().isEmpty ||
        _descriptionController.text.trim().isEmpty ||
        _locationController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('⚠️ Please fill all fields')),
      );
      return;
    }

    setState(() => _isLoading = true);

    final uri = Uri.parse(
        'http://10.210.246.254/Aces-flutter-api/admin/events/edit_event.php');
    var request = http.MultipartRequest('POST', uri);

    request.fields['id'] = widget.event['id'].toString();
    request.fields['title'] = _titleController.text.trim();
    request.fields['description'] = _descriptionController.text.trim();
    request.fields['event_date'] = _eventDate.toIso8601String().split('T')[0];
    request.fields['location'] = _locationController.text.trim();

    if (_selectedImage != null) {
      request.files.add(await http.MultipartFile.fromPath(
        'image',
        _selectedImage!.path,
        filename: _selectedImage!.path.split('/').last,
      ));
    }

    final response = await request.send();

    setState(() => _isLoading = false);

    final respStr = await response.stream.bytesToString();
    print('Response: $respStr');

    if (respStr.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('❌ Empty server response')),
      );
      return;
    }

    final respJson = json.decode(respStr);

    if (respJson['status'] == 'success') {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('✅ Event updated successfully!')),
      );
      Navigator.pop(context, true);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
            content:
                Text('❌ ${respJson['message'] ?? 'Failed to update event'}')),
      );
    }
  }

  Future<void> _pickEventDate() async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _eventDate,
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
      builder: (context, child) {
        return Theme(data: ThemeData.dark(), child: child!);
      },
    );
    if (picked != null && picked != _eventDate) {
      setState(() => _eventDate = picked);
    }
  }

  @override
  Widget build(BuildContext context) {
    String imageUrl = widget.event['image'] ?? '';
    if (imageUrl.isNotEmpty && !imageUrl.startsWith('http')) {
      imageUrl = 'http://10.210.246.254/Aces-flutter-api/' + imageUrl;
    }

    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        title: const Text('Edit Event'),
        backgroundColor: Colors.deepPurple,
      ),
      body: Padding(
        padding: const EdgeInsets.all(20),
        child: _isLoading
            ? const Center(child: CircularProgressIndicator())
            : SingleChildScrollView(
                child: Column(
                  children: [
                    _buildTextField(
                        controller: _titleController,
                        label: 'Title',
                        icon: Icons.title),
                    const SizedBox(height: 20),
                    _buildTextField(
                        controller: _descriptionController,
                        label: 'Description',
                        icon: Icons.description,
                        maxLines: 4),
                    const SizedBox(height: 20),
                    _buildTextField(
                        controller: _locationController,
                        label: 'Location',
                        icon: Icons.location_on),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        Icon(Icons.calendar_today,
                            color: Colors.deepPurpleAccent),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Text(
                            'Event Date: ${_eventDate.toLocal().toString().split(' ')[0]}',
                            style: const TextStyle(color: Colors.white),
                          ),
                        ),
                        IconButton(
                          icon: const Icon(Icons.edit_calendar,
                              color: Colors.deepPurpleAccent),
                          onPressed: _pickEventDate,
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        Icon(Icons.photo, color: Colors.deepPurpleAccent),
                        const SizedBox(width: 10),
                        Text(
                          _selectedImage == null
                              ? 'Select Event Photo'
                              : 'Photo Selected',
                          style: const TextStyle(color: Colors.white),
                        ),
                        const Spacer(),
                        IconButton(
                          icon: const Icon(Icons.photo_library,
                              color: Colors.deepPurpleAccent),
                          onPressed: _pickEventImage,
                        ),
                      ],
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 10),
                      child: _selectedImage != null
                          ? Image.file(
                              _selectedImage!,
                              height: 150,
                              width: 150,
                              fit: BoxFit.cover,
                            )
                          : (imageUrl.isNotEmpty)
                              ? Image.network(
                                  imageUrl,
                                  height: 150,
                                  width: 150,
                                  fit: BoxFit.cover,
                                )
                              : const Text(
                                  "No image selected",
                                  style: TextStyle(color: Colors.white70),
                                ),
                    ),
                    const SizedBox(height: 30),
                    ElevatedButton.icon(
                      onPressed: _updateEvent,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.deepPurple,
                        minimumSize: const Size(double.infinity, 50),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12)),
                      ),
                      icon: const Icon(Icons.save, color: Colors.white),
                      label: const Text('Save Changes',
                          style: TextStyle(fontSize: 18, color: Colors.white)),
                    )
                  ],
                ),
              ),
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    int maxLines = 1,
  }) {
    return TextField(
      controller: controller,
      maxLines: maxLines,
      style: const TextStyle(color: Colors.white),
      decoration: InputDecoration(
        labelText: label,
        labelStyle: const TextStyle(color: Colors.white70),
        prefixIcon: Icon(icon, color: Colors.deepPurpleAccent),
        filled: true,
        fillColor: Colors.white10,
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
      ),
    );
  }
}
