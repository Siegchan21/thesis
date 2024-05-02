import { Group } from './Classes.js';
import { Professor } from './Classes.js';
import { CourseClass } from './Classes.js';
import { Room } from './Classes.js';
import { Slot } from './Classes.js';
import { requests } from 'requests';
import { json } from 'json';
import { random, copy } from 'random';
import { ceil, log2 } from 'math';

Group.groups = [new Group("a", 10), new Group("b", 20), new Group("c", 30), new Group("d", 10), new Group("e", 40)];

Professor.professors = [new Professor("mutaqi"), new Professor("khalid"), new Professor("zafar"),
                        new Professor("basit"), new Professor("khalid_zaheer")];

CourseClass.classes = [new CourseClass("hu100a"), new CourseClass("hu100b"), new CourseClass("mt111"),
                       new CourseClass("hu160"), new CourseClass("cs101 lab", true),
                       new CourseClass("ch110"), new CourseClass("cs101"), new CourseClass("cs152")];

Room.rooms = [new Room("lt1", 20), new Room("lt2", 40), new Room("lt3", 60), new Room("lab", 100, true)];

Slot.slots = [new Slot("08:30", "10:00", "Mon"), new Slot("10:15", "11:45", "Mon"),
              new Slot("12:00", "13:30", "Mon"), new Slot("08:30", "10:00", "Tue"), new Slot("08:30", "11:30", "Mon", true)];

// TODO
// 0.  Running Simplified Class Scheduling - Done
// 0.5 Problem Instance to Binary String - Done
// 1.  Multiple days - Done
// 2.  Class Size - Done
// 2.25 Check Selection Function - Done
// 2.5 One group can attend only one class at a time - Done
// 3.  Multiple classes - Done
// 4.  Lab - Done

// Below chromosome parts are just to teach basic

// cpg = ["000000", "010001", "100100", "111010"] // course, professor, student group pair
// lts = ["00", "01"] // lecture theatres
// slots = ["00", "01"] // time slots

// ######### Chromosome ##############
// <CourseClass, Prof, Group, Slot, LT>   #
// ###################################


let max_score = null;

let cpg = [];
let lts = [];
let slots = [];
let bits_needed_backup_store = {};  // to improve performance


function bits_needed(x) {
    let r = bits_needed_backup_store.get(id(x));
    if (r === null || r === undefined) {
        r = Math.max(Math.ceil(Math.log2(x.length)), 1);
        bits_needed_backup_store[id(x)] = r;
    }
    return r;
}


function join_cpg_pair(_cpg) {
    let res = [];
    for (let i = 0; i < _cpg.length; i += 3) {
        res.push(_cpg[i] + _cpg[i + 1] + _cpg[i + 2]);
    }
    return res;
}


function convert_input_to_bin() {
    cpg = [CourseClass.find("hu100a"), Professor.find("mutaqi"), Group.find("a"),
           CourseClass.find("hu100b"), Professor.find("mutaqi"), Group.find("a"),
           CourseClass.find("mt111"), Professor.find("khalid"), Group.find("a"),
           CourseClass.find("cs152"), Professor.find("basit"), Group.find("a"),
           CourseClass.find("hu160"), Professor.find("mutaqi"), Group.find("b"),
           CourseClass.find("ch110"), Professor.find("zafar"), Group.find("e"),
           CourseClass.find("cs101"), Professor.find("basit"), Group.find("e"),
           CourseClass.find("cs101 lab"), Professor.find("basit"), Group.find("e")
           ];

    for (let _c = 0; _c < cpg.length; _c++) {
        if (_c % 3) {  // CourseClass
            cpg[_c] = (cpg[_c].toString(2)).padStart(bits_needed(CourseClass.classes), '0');
        } else if (_c % 3 === 1) {  // Professor
            cpg[_c] = (cpg[_c].toString(2)).padStart(bits_needed(Professor.professors), '0');
        } else {  // Group
            cpg[_c] = (cpg[_c].toString(2)).padStart(bits_needed(Group.groups), '0');
        }
    }

    cpg = join_cpg_pair(cpg);
    for (let r = 0; r < Room.rooms.length; r++) {
        lts.push((r.toString(2)).padStart(bits_needed(Room.rooms), '0'));
    }

    for (let t = 0; t < Slot.slots.length; t++) {
        slots.push((t.toString(2)).padStart(bits_needed(Slot.slots), '0'));
    }

    max_score = (cpg.length - 1) * 3 + cpg.length * 3;
}


function course_bits(chromosome) {
    let i = 0;

    return chromosome.slice(i, i + bits_needed(CourseClass.classes));
}


function professor_bits(chromosome) {
    let i = bits_needed(CourseClass.classes);

    return chromosome.slice(i, i + bits_needed(Professor.professors));
}


function group_bits(chromosome) {
    let i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors);

    return chromosome.slice(i, i + bits_needed(Group.groups));
}


function slot_bits(chromosome) {
    let i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors) +
        bits_needed(Group.groups);

    return chromosome.slice(i, i + bits_needed(Slot.slots));
}


function lt_bits(chromosome) {
    let i = bits_needed(CourseClass.classes) + bits_needed(Professor.professors) +
        bits_needed(Group.groups) + bits_needed(Slot.slots);

    return chromosome.slice(i, i + bits_needed(Room.rooms));
}


function slot_clash(a, b) {
    if (slot_bits(a) === slot_bits(b)) {
        return 1;
    }
    return 0;
}


// checks that a faculty member teaches only one course at a time.
function faculty_member_one_class(chromosome) {
    let scores = 0;
    for (let i = 0; i < chromosome.length - 1; i++) {  // select one cpg pair
        let clash = false;
        for (let j = i + 1; j < chromosome.length; j++) {  // check it with all other cpg pairs
            if (slot_clash(chromosome[i], chromosome[j]) &&
                    professor_bits(chromosome[i]) === professor_bits(chromosome[j])) {
                clash = true;
            }
        }
        if (!clash) {
            scores = scores + 1;
        }
    }
    return scores;
}


// check that a group member takes only one class at a time.
function group_member_one_class(chromosomes) {
    let scores = 0;

    for (let i = 0; i < chromosomes.length - 1; i++) {
        let clash = false;
        for (let j = i + 1; j < chromosomes.length; j++) {
            if (slot_clash(chromosomes[i], chromosomes[j]) &&
                    group_bits(chromosomes[i]) === group_bits(chromosomes[j])) {
                clash = true;
                break;
            }
        }
        if (!clash) {
            scores = scores + 1;
        }
    }
    return scores;
}


// checks that a course is assigned to an available classroom. 
function use_spare_classroom(chromosome) {
    let scores = 0;
    for (let i = 0; i < chromosome.length - 1; i++) {  // select one cpg pair
        let clash = false;
        for (let j = i + 1; j < chromosome.length; j++) {  // check it with all other cpg pairs
            if (slot_clash(chromosome[i], chromosome[j]) && lt_bits(chromosome[i]) === lt_bits(chromosome[j])) {
                clash = true;
            }
        }
        if (!clash) {
            scores = scores + 1;
        }
    }
    return scores;
}


// checks that the classroom capacity is large enough for the classes that
// are assigned to that classroom.
function classroom_size(chromosomes) {
    let scores = 0;
    for (let _c of chromosomes) {
        if (Group.groups[parseInt(group_bits(_c), 2)].size <= Room.rooms[parseInt(lt_bits(_c), 2)].size) {
            scores = scores + 1;
        }
    }
    return scores;
}


// check that room is appropriate for particular class/lab
function appropriate_room(chromosomes) {
    let scores = 0;
    for (let _c of chromosomes) {
        if (CourseClass.classes[parseInt(course_bits(_c), 2)].is_lab === Room.rooms[parseInt(lt_bits(_c), 2)].is_lab) {
            scores = scores + 1;
        }
    }
    return scores;
}


// check that lab is allocated appropriate time slot
function appropriate_timeslot(chromosomes) {
    let scores = 0;
    for (let _c of chromosomes) {
        if (CourseClass.classes[parseInt(course_bits(_c), 2)].is_lab === Slot.slots[parseInt(slot_bits(_c), 2)].is_lab_slot) {
            scores = scores + 1;
        }
    }
    return scores;
}


function evaluate(chromosomes) {
    let score = 0;
    score = score + use_spare_classroom(chromosomes);
    score = score + faculty_member_one_class(chromosomes);
    score = score + classroom_size(chromosomes);
    score = score + group_member_one_class(chromosomes);
    score = score + appropriate_room(chromosomes);
    score = score + appropriate_timeslot(chromosomes);
    return score / max_score;
}

function cost(solution) {
    // solution would be an array inside an array
    // it is because we use it as it is in genetic algorithms
    // too. Because, GA require multiple solutions i.e population
    // to work.
    return 1 / parseFloat(evaluate(solution));
}

function init_population(n) {
    let chromosomes = [];
    for (let _n = 0; _n < n; _n++) {
        let chromosome = [];
        for (let _c of cpg) {
            chromosome.push(_c + random.choice(slots) + random.choice(lts));
        }
        chromosomes.push(chromosome);
    }
    return chromosomes;
}


// Modified Combination of Row_reselect, Column_reselect
function mutate(chromosome) {
    let rand_slot = random.choice(slots);
    let rand_lt = random.choice(lts);

    let a = random.randint(0, chromosome.length - 1);
    
    chromosome[a] = course_bits(chromosome[a]) + professor_bits(chromosome[a]) +
        group_bits(chromosome[a]) + rand_slot + rand_lt;
}


function crossover(population) {
    let a = random.randint(0, population.length - 1);
    let b = random.randint(0, population.length - 1);
    let cut = random.randint(0, population[0].length);  // assume all chromosome are of same len
    population.push(population[a].slice(0, cut).concat(population[b].slice(cut)));
}


function selection(population, n) {
    population.sort((a, b) => evaluate(b) - evaluate(a));
    while (population.length > n) {
        population.pop();
    }
}


function print_chromosome(chromosome) {
    console.log(CourseClass.classes[parseInt(course_bits(chromosome), 2)], " | ",
          Professor.professors[parseInt(professor_bits(chromosome), 2)], " | ",
          Group.groups[parseInt(group_bits(chromosome), 2)], " | ",
          Slot.slots[parseInt(slot_bits(chromosome), 2)], " | ",
          Room.rooms[parseInt(lt_bits(chromosome), 2)]);
}

// Simple Searching Neighborhood
// It randomly changes timeslot of a class/lab
function ssn(solution) {
    let rand_slot = random.choice(slots);
    let rand_lt = random.choice(lts);
    
    let a = random.randint(0, solution.length - 1);
    
    let new_solution = copy.deepcopy(solution);
    new_solution[a] = course_bits(solution[a]) + professor_bits(solution[a]) +
        group_bits(solution[a]) + rand_slot + lt_bits(solution[a]);
    return [new_solution];
}

// Swapping Neighborhoods
// It randomy selects two classes and swap their time slots
function swn(solution) {
    let a = random.randint(0, solution.length - 1);
    let b = random.randint(0, solution.length - 1);
    let new_solution = copy.deepcopy(solution);
    let temp = slot_bits(solution[a]);
    new_solution[a] = course_bits(solution[a]) + professor_bits(solution[a]) +
        group_bits(solution[a]) + slot_bits(solution[b]) + lt_bits(solution[a]);

    new_solution[b] = course_bits(solution[b]) + professor_bits(solution[b]) +
        group_bits(solution[b]) + temp + lt_bits(solution[b]);
    return [new_solution];
}

function acceptance_probability(old_cost, new_cost, temperature) {
    if (new_cost < old_cost) {
        return 1.0;
    } else {
        return Math.exp((old_cost - new_cost) / temperature);
    }
}


function genetic_algorithm(num_schedules) {
    let generation = 0;
    convert_input_to_bin();
    let populations = Array.from({ length: num_schedules }, () => init_population(3)); // Initialize multiple populations
    // Or you can use a traditional loop
    // let populations = [];
    // for (let i = 0; i < num_schedules; i++) {
    //     populations.push(init_population(3));
    // }

    console.log("\n------------- Genetic Algorithm --------------\n");

    while (true) {
        if (generation === 10) {  // Terminate after 500 generations
            console.log("Generations:", generation);
            for (let schedule_idx = 0; schedule_idx < populations.length; schedule_idx++) {
                console.log("\nSchedule", schedule_idx + 1);
                console.log("Best Chromosome fitness value", evaluate(max(populations[schedule_idx], key=evaluate)));
                console.log("Best Chromosome:");
                for (let lec of max(populations[schedule_idx], key=evaluate)) {
                    print_chromosome(lec);
                }
            }
            break;
        }

        // Otherwise continue evolution
        for (let schedule_idx = 0; schedule_idx < populations.length; schedule_idx++) {
            for (let _ = 0; _ < populations[schedule_idx].length; _++) {
                crossover(populations[schedule_idx]);
                selection(populations[schedule_idx], 5);
                mutate(populations[schedule_idx][_]);
            }
        }

        generation += 1;
    }

    console.log("Finished evolving multiple schedules");
}


function main() {
    random.seed();
    let num_schedules = 5;  // Define how many schedules you want to generate
    genetic_algorithm(num_schedules);
}
main();


