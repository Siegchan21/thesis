class Group:
    groups = None

    def __init__(self, name, size):
        self.name = name
        self.size = size

    @staticmethod
    def find(name):
        for i in range(len(Group.groups)):
            if Group.groups[i].name == name:
                return i
        return -1

    def __repr__(self):
        return "Group: " + self.name + ", Size: " + str(self.size)


class Professor:
    professors = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        for i in range(len(Professor.professors)):
            if Professor.professors[i].name == name:
                return i
        return -1

    def __repr__(self):
        return "Professor: " + self.name


class CourseClass:
    classes = None

    def __init__(self, code, is_lab=False):
        self.code = code
        self.is_lab = is_lab

    @staticmethod
    def find(code):
        for i in range(len(CourseClass.classes)):
            if CourseClass.classes[i].code == code:
                return i
        return -1

    def __repr__(self):
        return "CourseClass: " + self.code


class Room:
    rooms = None

    def __init__(self, name, size, is_lab=False):
        self.name = name
        self.size = size
        self.is_lab = is_lab

    @staticmethod
    def find(name):
        for i in range(len(Room.rooms)):
            if Room.rooms[i].name == name:
                return i
        return -1

    def __repr__(self):
        return "Room: " + self.name + " Size: " + str(self.size)


class Slot:
    def __init__(self, start_time, end_time, day, is_lab_slot=False):
        self.start_time = start_time
        self.end_time = end_time
        self.day = day
        self.is_lab_slot = is_lab_slot

    def __repr__(self):
        return f"Slot({self.start_time}, {self.end_time}, {self.day}, {self.is_lab_slot})"

