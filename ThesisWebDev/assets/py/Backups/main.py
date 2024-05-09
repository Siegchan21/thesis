import requests
import random
import copy
from math import ceil, log2
import math

class Group:
    groups = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        return Group.groups.index(name) if name in Group.groups else -1

    def __repr__(self):
        return "Group: " + self.name

class Professor:
    professors = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        return Professor.professors.index(name) if name in Professor.professors else -1

    def __repr__(self):
        return "Professor: " + self.name


class CourseClass:
    classes = None

    def __init__(self, code):
        self.code = code

    @staticmethod
    def find(code):
        return CourseClass.classes.index(code) if code in CourseClass.classes else -1

    def __repr__(self):
        return "CourseClass: " + self.code

class Room:
    rooms = None

    def __init__(self, name):
        self.name = name

    @staticmethod
    def find(name):
        return Room.rooms.index(name) if name in Room.rooms else -1

    def __repr__(self):
        return "Room: " + self.name

class Slot:
    slots = None

    def __init__(self, start, end, day):
        self.start = start
        self.end = end
        self.day = day

    def __repr__(self):
        return "Slot: " + self.start + "-" + self.end + " Day: " + self.day
    
def fetch_data_from_url(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            return response.json()  # Assuming the response is in JSON format
        else:
            print("Failed to fetch data from the URL:", response.status_code)
            return None
    except Exception as e:
        print("An error occurred while fetching data:", e)
        return None
    
def fetch_data_from_url(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            return response.json()  # Assuming the response is in JSON format
        else:
            print("Failed to fetch data from the URL:", response.status_code)
            return None
    except Exception as e:
        print("An error occurred while fetching data:", e)
        return None

def populate_groups():
    group_data = fetch_data_from_url('http://localhost/thesis/ThesisWebDev/assets/php/filterSectionLibrary.php')
    print("Fetched Group Data:")
    for item in group_data:
        print("Section:", item)

def populate_professors():
    professor_data = fetch_data_from_url('http://localhost/thesis/ThesisWebDev/assets/php/filterInstructorLibrary.php')
    print("Fetched Professor Data:")
    for item in professor_data:
        print("Instructor:", item)

def populate_course_classes():
    CourseClass.classes = []
    # Fetch course class data from the URL
    course_class_data = fetch_data_from_url('http://localhost/thesis/ThesisWebDev/assets/php/filterSubjectBackend.php')
    if course_class_data:
        print("Fetched Course Class Data:")
        if isinstance(course_class_data, list):  # Check if the fetched data is a list
            for code in course_class_data:
                if isinstance(code, str):  # Check if each item in the list is a string
                    CourseClass.classes.append(CourseClass(code))  # Initialize CourseClass instance
                    print("Course Code:", code)
                else:
                    print("Invalid data format:", code)
        else:
            print("Invalid course class data format:", course_class_data)
    else:
        print("No course class data available.")

def populate_rooms():
    room_data = fetch_data_from_url('http://localhost/thesis/ThesisWebDev/assets/php/filterRoomBackend.php')
    Room.rooms = []  # Initialize Room.rooms as an empty list
    if room_data:
        print("Fetched Room Data:")
        if isinstance(room_data, list):  # Check if the fetched data is a list
            for room_name in room_data:
                if isinstance(room_name, str):  # Check if each item in the list is a string
                    # Assuming each item in room_data is a room name
                    Room.rooms.append(Room(room_name))  # Initialize Room instance
                    print("Room:", room_name)
                else:
                    print("Invalid room data:", room_name)
        else:
            print("Invalid room data format:", room_data)
    else:
        print("No room data available or failed to fetch room data.")



def populate_slots():
    global slots
    days = ["Tue", "Wed", "Thu", "Fri"]
    time_slots = [
        ("07:00", "08:30"), ("08:30", "10:00"), ("10:00", "11:30"),
        ("13:00", "14:30"), ("14:30", "16:00"), ("16:00", "17:30")
    ]
    slots = [
        Slot(start, end, day)
        for day in days
        for start, end in time_slots
    ]
    if slots:
        print("Populated Slots:")
        for slot in slots:
            print(slot)
    else:
        print("Error: No slots data available or failed to populate slots.")

def populate_classes_from_data():
    populate_groups()
    populate_professors()
    populate_course_classes()
    populate_rooms()
    populate_slots()

    # Populate the lts list from the fetched room data
    global lts
    lts = [Room.find(room.name) for room in Room.rooms]  # Assuming Room.rooms contains Room instances
    if lts:
        print("Populated LTS (Rooms):")
        for room_index in lts:
            print("Room:", Room.rooms[room_index])
    else:
        print("Error: No LTS (Rooms) data available or failed to populate LTS (Rooms).")


max_score = None

cpg = []
lts = []
slots = []
bits_needed_backup_store = {}  # to improve performance


def bits_needed(x):
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


def convert_input_to_bin():
    global cpg, lts, slots, max_score

    # Initialize professors attribute if it's None
    if Professor.professors is None:
        Professor.professors = []  # Initialize professors attribute as an empty list

    cpg = []
    # Fetch course class data from the URL
    course_class_data = fetch_data_from_url('http://localhost/thesis/ThesisWebDev/assets/php/filterSubjectBackend.php')
    if course_class_data:
        print("Fetched Course Class Data:")
        if isinstance(course_class_data, list):  # Check if the fetched data is a list
            for item in course_class_data:
                if isinstance(item, dict):
                    # Assuming the data structure of each item in course_class_data
                    course_code = item.get('code', '')  # Fetch course code if available
                    professor_name = item.get('professor', '')  # Fetch professor name if available
                    group_name = item.get('group', '')  # Fetch group name if available
                    # Append the course class data to the cpg list
                    cpg.extend([CourseClass.find(course_code), Professor.find(professor_name), Group.find(group_name)])
                else:
                    print("Invalid data format:", item)
        else:
            print("Invalid course class data format:", course_class_data)
    else:
        print("No course class data available.")

    max_score = (len(cpg) - 1) * 3 + len(cpg) * 3


def course_bits(chromosome):
    return chromosome[:bits_needed(CourseClass.classes)]

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
    if max_score is None:
        print("Max score is not initialized.")
        return 0  # Return 0 if max_score is not initialized
    else:
        score = score + use_spare_classroom(chromosomes)
        score = score + faculty_member_one_class(chromosomes)
        score = score + group_member_one_class(chromosomes)
        return score / max_score if max_score != 0 else 0  # Return 0 if max_score is 0

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
            chromosome.append(_c + random.choice(slots) + random.choice(lts))
        if not chromosome:  # Check if the chromosome is empty
            print("Empty chromosome generated.")
        chromosomes.append(chromosome)
    return chromosomes


def mutate(chromosome):
    global slots
    if not slots:
        print("Error: Slots not populated. Call populate_slots before mutate.")
        return
    
    if not chromosome:  # Check if the chromosome is empty
        print("Error: Empty chromosome provided for mutation.")
        return

    rand_slot = random.choice(slots)
    rand_lt = random.choice(lts)

    a = random.randint(0, len(chromosome) - 1)
    
    chromosome[a] = course_bits(chromosome[a]) + professor_bits(chromosome[a]) +\
        group_bits(chromosome[a]) + rand_slot + rand_lt

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

    # print("Original population:")
    # print(population)
    print("\n------------- Genetic Algorithm --------------\n")
    while True:
        
        # if termination criteria are satisfied, stop.
        if evaluate(max(population, key=evaluate)) == 1 or generation == 5:
            print("Generations:", generation)
            print("Best Chromosome fitness value", evaluate(max(population, key=evaluate)))
            print("Best Chromosome: ", max(population, key=evaluate))
            for lec in max(population, key=evaluate):
                print_chromosome(lec)
            break
        
        # Otherwise continue
        else:
            for _c in range(len(population)):
                crossover(population)
                selection(population, 5)
                
                # selection(population[_c], len(cpg))
                mutate(population[_c])

        generation = generation + 1
        # print("Gen: ", generation)

    # print("Population", len(population))


def main():
    # Fetch and initialize classes, professors, rooms, etc. from data
    populate_classes_from_data()
    # Convert input data to binary representation
    convert_input_to_bin()
    # Initialize random seed for reproducibility
    random.seed()
    # Run the genetic algorithm to find optimal/near-optimal solutions
    genetic_algorithm()

# Entry point of the program
if __name__ == "__main__":
    main()