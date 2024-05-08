import requests
import json
import random, copy
from Classes import *
from math import ceil, log2
import math


url = 'http://localhost/thesis/ThesisWebDev/assets/php/filterInstructorBackend.php'

responseInstructor = requests.post(url)

new_professor_names = responseInstructor.json()

if not isinstance(new_professor_names, list):
    new_professor_names = [new_professor_names]

# Add the new professor to the list

Professor.professors = []

for professor_name in new_professor_names:
    existing_index = Professor.find(professor_name)
    if existing_index == -1:
        Professor.professors.append(Professor(professor_name))
    else:
        print("Professor already exists:", Professor.professors[existing_index])
        
Group.groups = [Group("a", 10), Group("b", 20), Group("c", 30), Group("d", 10), Group("e", 40)]

groups_instance = ["a","b","c","d","e"]

course_class_instance = ["hu100a","hu100b","mt111","hu160","cs101 lab","ch110","cs101","cs152"]

CourseClass.classes = [CourseClass("hu100a"), CourseClass("hu100b"), CourseClass("mt111"),
                       CourseClass("hu160"), CourseClass("cs101 lab", is_lab=True),
                       CourseClass("ch110"), CourseClass("cs101"), CourseClass("cs152")]

Room.rooms = [Room("lt1", 20), Room("lt2", 40), Room("lt3", 60), Room("lab", 100, is_lab=True)]

Slot.slots = [Slot("08:30", "10:00", "Mon"), Slot("10:15", "11:45", "Mon"),
              Slot("12:00", "13:30", "Mon"), Slot("08:30", "10:00", "Tue"), Slot("08:30", "11:30", "Mon", is_lab_slot=True)]

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

    # Generate course-professor-group pairs
    for course in CourseClass.classes:
        for professor in Professor.professors:
            for group in Group.groups:
                cpg.append((course.code, professor.name, group.name))

    # Calculate max score
    max_score = (len(cpg) - 1) * 3 + len(cpg) * 3

    # Generate binary representation for lecture theatres
    for r in range(len(Room.rooms)):
        lts.append((bin(r)[2:]).rjust(bits_needed(Room.rooms), '0'))

    # Generate binary representation for time slots
    for t in range(len(Slot.slots)):
        slots.append((bin(t)[2:]).rjust(bits_needed(Slot.slots), '0'))

    # Print generated values for verification
    print("Chromosome Parts:", cpg)
    print("LTs:", lts)
    print("Slots:", slots)


def course_bits(course_code):
    # Find the index of the course with the given code
    course_index = CourseClass.find(course_code)
    # Extract the binary representation of the course index
    return bin(course_index)[2:].zfill(bits_needed(CourseClass.classes))



def professor_bits(chromosome):
    return ''.join(str(bit) for bit in chromosome[bits_needed(CourseClass.classes): bits_needed(CourseClass.classes) +
                                                             bits_needed(Professor.professors)])

def group_bits(chromosome):
    return chromosome[bits_needed(CourseClass.classes) + bits_needed(Professor.professors):
                     bits_needed(CourseClass.classes) + bits_needed(Professor.professors) +
                     bits_needed(Group.groups)]

def slot_bits(chromosome):
    start_index = bits_needed(CourseClass.classes) + bits_needed(Professor.professors) + \
                  bits_needed(Group.groups)
    end_index = start_index + bits_needed(Slot.slots)
    print("Start index:", start_index)
    print("End index:", end_index)
    print("Chromosome:", chromosome)
    slot_bits_str = chromosome[start_index:end_index]
    print("Slot bits:", slot_bits_str)
    return slot_bits_str

def lt_bits(chromosome):
    return chromosome[bits_needed(CourseClass.classes) + bits_needed(Professor.professors) +
                       bits_needed(Group.groups) + bits_needed(Slot.slots):]

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

# checks that the classroom capacity is large enough for the classes that
# are assigned to that classroom.
def classroom_size(chromosomes):
    scores = 0
    for _c in chromosomes:
        if Group.groups[int(group_bits(_c), 2)].size <= Room.rooms[int(lt_bits(_c), 2)].size:
            scores = scores + 1
    return scores


# check that room is appropriate for particular class/lab
def appropriate_room(chromosomes):
    scores = 0
    for _c in chromosomes:
        if CourseClass.classes[int(course_bits(_c), 2)].is_lab == Room.rooms[int(lt_bits(_c), 2)].is_lab:
            scores = scores + 1
    return scores

# check that lab is allocated appropriate time slot
def appropriate_timeslot(chromosomes):
    scores = 0
    for _c in chromosomes:
        if CourseClass.classes[int(course_bits(_c), 2)].is_lab == Slot.slots[int(slot_bits(_c), 2)].is_lab_slot:
            scores = scores + 1
    return scores

def evaluate(chromosomes):
    global max_score
    score = 0
    # Your evaluation logic goes here
    return score / max_score

def cost(solution):
    # solution would be an array inside an array
    # it is because we use it as it is in genetic algorithms
    # too. Because, GA require multiple solutions i.e population
    # to work.
    return 1 / float(evaluate(solution))

def init_population(n):
    global cpg, lts, slots
    print("Length of cpg:", len(cpg))
    print("Length of slots:", len(slots))
    print("Length of lts:", len(lts))
    chromosomes = []
    for _ in range(n):
        chromosome = []
        for _ in range(len(cpg)):
            chromosome.append(random.choice(cpg) + (random.choice(slots),) + (random.choice(lts),))
        chromosomes.append(chromosome)
    return chromosomes


def mutate(chromosome):
    rand_slot = random.choice(slots)
    rand_lt = random.choice(lts)

    a = random.randint(0, len(chromosome) - 1)
    
    course_code = chromosome[a][0]  # Extracting only the course code
    
    chromosome[a] = (course_bits(course_code) + ''.join(professor_bits(chromosome[a])) +
                 ''.join(group_bits(chromosome[a])) + str(rand_slot) + str(rand_lt))

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
    course_bits_str = ''.join(course_bits(chromosome))
    course_index = int(course_bits_str, 2)
    course_code = CourseClass.classes[course_index].code if 0 <= course_index < len(CourseClass.classes) else "N/A"

    professor_bits_str = ''.join(professor_bits(chromosome))
    professor_index = int(professor_bits_str, 2)
    professor_name = Professor.professors[professor_index].name if 0 <= professor_index < len(Professor.professors) else "N/A"

    group_bits_str = ''.join(group_bits(chromosome))
    group_index = int(group_bits_str, 2)
    group_name = Group.groups[group_index].name if 0 <= group_index < len(Group.groups) else "N/A"

    slot_bits_str = ''.join(slot_bits(chromosome))
    slot_index = int(slot_bits_str, 2)
    slot_info = Slot.slots[slot_index].time + " " + Slot.slots[slot_index].day if 0 <= slot_index < len(Slot.slots) else "N/A"

    lt_bits_str = ''.join(lt_bits(chromosome))
    lt_index = int(lt_bits_str, 2)
    lt_name = Room.rooms[lt_index].name if 0 <= lt_index < len(Room.rooms) else "N/A"

    print("Chromosome:", chromosome)
    print(course_code, " | ", professor_name, " | ", group_name, " | ", slot_info, " | ", lt_name)





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


def genetic_algorithm(num_schedules):
    generation = 0
    convert_input_to_bin()
    populations = [init_population(3) for _ in range(num_schedules)]  # Initialize multiple populations

    print("\n------------- Genetic Algorithm --------------\n")

    while True:
        if generation == 10:  # Terminate after 500 generations
            print("Generations:", generation)
            for schedule_idx, population in enumerate(populations):
                print("\nSchedule", schedule_idx + 1)
                print("Best Chromosome fitness value", evaluate(max(population, key=evaluate)))
                print("Best Chromosome:")
                for lec in max(population, key=evaluate):
                    print_chromosome(lec)
            break

        # Otherwise continue evolution
        for schedule_idx, population in enumerate(populations):
            for _ in range(len(population)):
                crossover(population)
                selection(population, 5)
                mutate(population[_])

        generation += 1

    print("Finished evolving multiple schedules")


def main():
    random.seed()
    num_schedules = 1  # Define how many schedules you want to generate
    
    convert_input_to_bin()  # Populate cpg, slots, and lts
    
    genetic_algorithm(num_schedules)

main()