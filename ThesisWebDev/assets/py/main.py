import requests
import math
from math import ceil, log2
import random
import json
from datetime import datetime, timedelta 
from collections.abc import Iterable
import copy

class Group:
    groups = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        for i, group in enumerate(Group.groups):
            if group.name == name:
                return i
        return -1

    def __repr__(self):
        return f"Group: {self.name}"


class Professor:
    professors = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        for i, professor in enumerate(Professor.professors):
            if professor.name == name:
                return i
        return -1

    def __repr__(self):
        return f"Professor: {self.name}"

class CourseClass:
    classes = None

    def __init__(self, name):
        self.name = name

    def get_name(self):
        return self.name

    @staticmethod
    def find(name):
        for i, course_class in enumerate(CourseClass.classes):
            if course_class.name == name:
                return i
        return -1

    def __repr__(self):
        return f"CourseClass: {self.name}"


class Room:
    rooms = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        for i, room in enumerate(Room.rooms):
            if room.name == name:
                return i
        return -1

    def __repr__(self):
        return f"Room: {self.name}"


class Slot:
    slots = None

    @classmethod
    def populate_slots(cls):
        cls.slots = []

        # Provided time slot data
        slot_data = [
            {"name": "07:00-08:30 Tue"}, {"name": "08:45-10:15 Tue"}, {"name": "10:30-12:00 Tue"},
            {"name": "13:00-14:30 Tue"}, {"name": "14:45-16:15 Tue"}, {"name": "16:30-18:00 Tue"},
            {"name": "07:00-08:30 Wed"}, {"name": "08:45-10:15 Wed"}, {"name": "10:30-12:00 Wed"},
            {"name": "13:00-14:30 Wed"}, {"name": "14:45-16:15 Wed"}, {"name": "16:30-18:00 Wed"},
            {"name": "07:00-08:30 Thu"}, {"name": "08:45-10:15 Thu"}, {"name": "10:30-12:00 Thu"},
            {"name": "13:00-14:30 Thu"}, {"name": "14:45-16:15 Thu"}, {"name": "16:30-18:00 Thu"},
            {"name": "07:00-08:30 Fri"}, {"name": "08:45-10:15 Fri"}, {"name": "10:30-12:00 Fri"},
            {"name": "13:00-14:30 Fri"}, {"name": "14:45-16:15 Fri"}, {"name": "16:30-18:00 Fri"}
        ]

        # Extract time slots from the provided data
        for slot in slot_data:
            cls.slots.append(slot["name"])

def fetch_data_from_url(urls):
    response = requests.get(urls)
    if response.status_code == 200:
        return response.json()
    else:
        print(f"Failed to fetch data from {urls}")
        return []

max_score = None

cpg = []
lts = []
slots = []
bits_needed_backup_store = {}  # to improve performance


def bits_needed(x):
    if isinstance(x, int):
        return int(ceil(log2(x)))
    else:
        global bits_needed_backup_store
        r = bits_needed_backup_store.get(id(x))
        if r is None:
            r = int(ceil(log2(len(x))))
            bits_needed_backup_store[id(x)] = r
        return max(r, 1)

def join_cpg_pair(_cpg):
    res = []
    for i in range(0, len(_cpg), 3):
        res.append(_cpg[i] + _cpg[i + 1] + _cpg[i + 2])
    return res

def generate_time_slots():
    start_time_morning = datetime.strptime('07:00', '%H:%M')
    end_time_morning = datetime.strptime('11:30', '%H:%M')
    start_time_afternoon = datetime.strptime('13:00', '%H:%M')
    end_time_afternoon = datetime.strptime('17:30', '%H:%M')

    time_slots = []

    current_time_morning = start_time_morning
    current_time_afternoon = start_time_afternoon

    while current_time_morning < end_time_morning:
        time_slots.append(current_time_morning.strftime('%H:%M') + '-' + (current_time_morning + timedelta(minutes=90)).strftime('%H:%M'))
        current_time_morning += timedelta(minutes=90)

    while current_time_afternoon < end_time_afternoon:
        time_slots.append(current_time_afternoon.strftime('%H:%M') + '-' + (current_time_afternoon + timedelta(minutes=90)).strftime('%H:%M'))
        current_time_afternoon += timedelta(minutes=90)

    return time_slots

def fetch_professor_for_course(course_name):
    try:
        response = requests.get('http://localhost/thesis/ThesisWebDev/assets/php/filterInstructorBackend.php', params={'courseName': course_name})
        response.raise_for_status()
        professors = response.json()
        if professors:  # Check if the list is not empty
            return professors[0]  # Return the first professor name
        else:
            return None
    except requests.exceptions.RequestException as e:
        print(f"Error fetching professor for course '{course_name}': {e}")
        return None

    
def fetch_group_for_course(course_name):
    try:
        response = requests.get('http://localhost/thesis/ThesisWebDev/assets/php/filterLevelBackend.php', params={'courseName': course_name})
        response.raise_for_status()
        groups_name = response.json()
        if groups_name:
            return groups_name[0]
        else:
            return None
    except requests.exceptions.RequestException as e:
        print(f"Error fetching groups for course '{course_name}': {e}")
        return None

def convert_input_to_bin():
    global cpg, lts, slots, max_score

    try:
        course_data = fetch_data_from_url("http://localhost/thesis/ThesisWebDev/assets/php/filterSubjectBackend.php")
        room_data = fetch_data_from_url("http://localhost/thesis/ThesisWebDev/assets/php/filterRoomBackend.php")
        professor_data = fetch_data_from_url("http://localhost/thesis/ThesisWebDev/assets/php/filterInstructorLibrary.php")
        group_data = fetch_data_from_url("http://localhost/thesis/ThesisWebDev/assets/php/filterLevelLibrary.php")

        cpg = []
        lts = [] 
        slots = []

        # Process room data
        Room.rooms = [Room(name=room) for room in room_data]

        # Populate CourseClass list with course objects
        course_classes = [CourseClass(name) for name in course_data]
        CourseClass.classes = course_classes

        # Fetch and populate Professor list
        professors = [Professor(name) for name in professor_data]
        Professor.professors = professors

        # Fetch and populate Group list
        groups = [Group(name) for name in group_data]
        Group.groups = groups

        Slot.populate_slots()

        # Assuming course_data is a list of course names
        if isinstance(course_data, list):
            for course_name in course_data:
                # Assuming course_name is a string representing the name of the course
                if isinstance(course_name, str):
                    # Make an HTTP GET request to fetch additional data for the course
                    professor_name = fetch_professor_for_course(course_name)
                    group_name = fetch_group_for_course(course_name)

                    if professor_name is not None and group_name is not None:
                        # Find the indexes of course, professor, and group
                        professor_index = Professor.find(professor_name)
                        course_index = CourseClass.find(course_name)
                        group_index = Group.find(group_name)

                        if course_index != -1 and professor_index != -1 and group_index != -1:
                            # Convert indexes to binary strings and pad to uniform length
                            binary_representation = (bin(course_index)[2:].rjust(bits_needed(len(CourseClass.classes)), '0') +
                                                     bin(professor_index)[2:].rjust(bits_needed(len(Professor.professors)), '0') +
                                                     bin(group_index)[2:].rjust(bits_needed(len(Group.groups)), '0'))
                            cpg.append(binary_representation)
                        else:
                            print(f"Error: Course, Professor, or Group not found for '{course_name}'.")
                    else:
                        print(f"Error: Professor '{professor_name}' or Group '{group_name}' not found for course '{course_name}'.")
                else:
                    print("Error: Course data item is not in the expected format.")
        else:
            print("Error: Course data is not in the expected format.")

        # Find the maximum length of binary representations in cpg
        max_length = max(len(binary) for binary in cpg)

        # Pad all binary representations in cpg to the maximum length
        cpg = [binary.rjust(max_length, '0') for binary in cpg]

        # Append rooms to lts list
        for i, room in enumerate(Room.rooms):
            lts.append(bin(i)[2:].rjust(bits_needed(len(Room.rooms)), '0'))

        slot_data = [
            {"name": "07:00-08:30 Tue"}, {"name": "08:45-10:15 Tue"}, {"name": "10:30-12:00 Tue"},
            {"name": "13:00-14:30 Tue"}, {"name": "14:45-16:15 Tue"}, {"name": "16:30-18:00 Tue"},
            {"name": "07:00-08:30 Wed"}, {"name": "08:45-10:15 Wed"}, {"name": "10:30-12:00 Wed"},
            {"name": "13:00-14:30 Wed"}, {"name": "14:45-16:15 Wed"}, {"name": "16:30-18:00 Wed"},
            {"name": "07:00-08:30 Thu"}, {"name": "08:45-10:15 Thu"}, {"name": "10:30-12:00 Thu"},
            {"name": "13:00-14:30 Thu"}, {"name": "14:45-16:15 Thu"}, {"name": "16:30-18:00 Thu"},
            {"name": "07:00-08:30 Fri"}, {"name": "08:45-10:15 Fri"}, {"name": "10:30-12:00 Fri"},
            {"name": "13:00-14:30 Fri"}, {"name": "14:45-16:15 Fri"}, {"name": "16:30-18:00 Fri"}
        ]
        for i, slot in enumerate(slot_data):
            slots.append(bin(i)[2:].rjust(bits_needed(len(slot_data)), '0'))

        # Calculate max_score based on the lengths of cpg, lts, and slots
        max_score = len(cpg) * len(lts) * len(slots)

        return cpg, lts, slots, max_score  # Return all necessary data
    except Exception as e:
        print(f"An error occurred during data conversion: {e}")
        return [], [], [], 0


def course_bits(chromosome):
    i = 0

    return chromosome[i:i + bits_needed(CourseClass.classes)]


def professor_bits(chromosome):
    i = bits_needed(CourseClass.classes)

    return chromosome[i: i + bits_needed(Professor.professors)]


def group_bits(chromosome):
    i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors)

    return chromosome[i:i + bits_needed(Group.groups)]


def slot_bits(chromosome):
    i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors) + \
        bits_needed(Group.groups)

    return chromosome[i:i + bits_needed(Slot.slots)]


def lt_bits(chromosome):
    i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors) + \
        bits_needed(Group.groups) + bits_needed(Slot.slots)

    return chromosome[i: i + bits_needed(Room.rooms)]


def slot_clash(a, b):
    if slot_bits(a) == slot_bits(b):
        return 1
    return 0

# checks that a faculty member teaches only one course at a time.
def faculty_member_one_class(chromosome):
    scores = 0
    for i in range(len(chromosome) - 1):  # select one cpg pair
        clash = False
        for j in range(i + 1, len(chromosome)):  # check it with all other cpg pairs
            if slot_clash(chromosome[i], chromosome[j])\
                    and professor_bits(chromosome[i]) == professor_bits(chromosome[j]):
                clash = True
                # print("These prof. have clashes")
                # print_chromosome(chromosome[i])
                # print_chromosome(chromosome[j])
        if not clash:
            scores = scores + 1
    return scores


# check that a group member takes only one class at a time.
def group_member_one_class(chromosomes):
    scores = 0

    for i in range(len(chromosomes) - 1):
        clash = False
        for j in range(i + 1, len(chromosomes)):
            if slot_clash(chromosomes[i], chromosomes[j]) and\
                    group_bits(chromosomes[i]) == group_bits(chromosomes[j]):
                # print("These classes have slot/lts clash")
                # print_chromosome(chromosomes[i])
                # print_chromosome(chromosomes[j])
                # print("____________")
                clash = True
                break
        if not clash:
            # print("These classes have no slot/lts clash")
            # print_chromosome(chromosomes[i])
            # print_chromosome(chromosomes[j])
            # print("____________")
            scores = scores + 1
    return scores


# checks that a course is assigned to an available classroom. 
def use_spare_classroom(chromosome):
    scores = 0
    for i in range(len(chromosome) - 1):  # select one cpg pair
        clash = False
        for j in range(i + 1, len(chromosome)):  # check it with all other cpg pairs
            if slot_clash(chromosome[i], chromosome[j]) and lt_bits(chromosome[i]) == lt_bits(chromosome[j]):
                # print("These classes have slot/lts clash")
                # printChromosome(chromosome[i])
                # printChromosome(chromosome[j])
                clash = True
        if not clash:
            scores = scores + 1
    return scores

def evaluate(chromosomes):
    global max_score
    score = 0
    score = score + use_spare_classroom(chromosomes)
    score = score + faculty_member_one_class(chromosomes)
    score = score + group_member_one_class(chromosomes)
    return score / max_score

def cost(solution):
    # solution would be an array inside an array
    # it is because we use it as it is in genetic algorithms
    # too. Because, GA require multiple solutions i.e population
    # to work.
    return 1 / float(evaluate(solution))

def init_population(n):
    global cpg, lts, slots
    chromosomes = []
    for _n in range(n):
        chromosome = []
        for _c in cpg:
            # Generate a random slot and LT for each course
            slot = random.choice(slots)
            lt = random.choice(lts)
            # Append binary representation of CourseClass, Professor, Group, Slot, and LT to the chromosome
            chromosome.append(course_bits(_c) + professor_bits(_c) + group_bits(_c) + slot + lt)
        chromosomes.append(chromosome)
    return chromosomes


# Modified Combination of Row_reselect, Column_reselect
def mutate(chromosome):
    # print("Before mutation: ", end="")
    # printChromosome(chromosome)

    rand_slot = random.choice(slots)
    rand_lt = random.choice(lts)

    a = random.randint(0, len(chromosome) - 1)
    
    chromosome[a] = course_bits(chromosome[a]) + professor_bits(chromosome[a]) +\
        group_bits(chromosome[a]) + rand_slot + rand_lt

    # print("After mutation: ", end="")
    # printChromosome(chromosome)


def crossover(population):
    a = random.randint(0, len(population) - 1)
    b = random.randint(0, len(population) - 1)
    cut = random.randint(0, len(population[0]))  # assume all chromosome are of same len
    population.append(population[a][:cut] + population[b][cut:])
    

def selection(population, n):
    population.sort(key=evaluate, reverse=True)
    while len(population) > n:
        population.pop()


def print_chromosome(chromosome):
    print(CourseClass.classes[int(course_bits(chromosome), 2)], " | ",
          Professor.professors[int(professor_bits(chromosome), 2)], " | ",
          Group.groups[int(group_bits(chromosome), 2)], " | ",
          Slot.slots[int(slot_bits(chromosome), 2)], " | ",
          Room.rooms[int(lt_bits(chromosome), 2)])


# Simple Searching Neighborhood
# It randomly changes timeslot of a class/lab
def ssn(solution):
    rand_slot = random.choice(slots)
    rand_lt = random.choice(lts)
    
    a = random.randint(0, len(solution) - 1)
    
    new_solution = copy.deepcopy(solution)
    new_solution[a] = course_bits(solution[a]) + professor_bits(solution[a]) +\
        group_bits(solution[a]) + rand_slot + lt_bits(solution[a])
    return [new_solution]

# Swapping Neighborhoods
# It randomy selects two classes and swap their time slots
def swn(solution):
    a = random.randint(0, len(solution) - 1)
    b = random.randint(0, len(solution) - 1)
    new_solution = copy.deepcopy(solution)
    temp = slot_bits(solution[a])
    new_solution[a] = course_bits(solution[a]) + professor_bits(solution[a]) +\
        group_bits(solution[a]) + slot_bits(solution[b]) + lt_bits(solution[a])

    new_solution[b] = course_bits(solution[b]) + professor_bits(solution[b]) +\
        group_bits(solution[b]) + temp + lt_bits(solution[b])
    # print("Diff", solution)
    # print("Meiw", new_solution)
    return [new_solution]

def acceptance_probability(old_cost, new_cost, temperature):
    if new_cost < old_cost:
        return 1.0
    else:
        return math.exp((old_cost - new_cost) / temperature)


def genetic_algorithm():
    generation = 0
    convert_input_to_bin()
    population = init_population(3)

    print("\n------------- Genetic Algorithm --------------\n")
    while True:
        # if termination criteria are satisfied, stop.
        if evaluate(max(population, key=evaluate)) == 1 or generation == 500:
            print("Generations:", generation)
            print("Best Chromosome fitness value", evaluate(max(population, key=evaluate)))
            print("Best Chromosome: ", max(population, key=evaluate))
            for lec in max(population, key=evaluate):
                print_chromosome(lec)
            break
        else:
            for _c in range(len(population)):
                crossover(population)
                selection(population, 5)
                mutate(population[_c])

        generation += 1

def main():
    global cpg, lts, slots, max_score  # Ensure max_score is accessible and modifiable within this function
    cpg, lts, slots, max_score = convert_input_to_bin()
    random.seed()
    genetic_algorithm()

if __name__ == "__main__":
    main()