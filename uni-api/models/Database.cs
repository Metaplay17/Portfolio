using api.Structures;
using Npgsql;
using System;

namespace api.models
{
    public class Database
    {
        public string connectionString;
        public Random _random;

        public Database()
        {
            connectionString = "Host=localhost;Username=postgres;Password=qazedcrfvs1A;Database=hackathon_abitura";
            _random = new Random();
        }

        public Applicant[] LoadApplicants()
        {
            var scores = new List<ApplicantScore>();
            Dictionary<string, Applicant> applicants = new Dictionary<string, Applicant>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                string sql = @"
                SELECT id, subject_id, test_ball, snils 
                FROM applicants_scores";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var score = new ApplicantScore
                        {
                            Id = reader.GetInt32(0),
                            SubjectId = reader.GetInt32(1),
                            TestBall = reader.GetInt32(2),
                            Snils = reader.GetInt64(3).ToString()
                        };
                        scores.Add(score);
                        if (!applicants.ContainsKey(score.Snils))
                        {
                            applicants.Add(score.Snils, new Applicant(score.Snils));
                        }
                    }
                }
            }

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = "SELECT snils, is_submit_original FROM applicants_data";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var appInfo = new ApplicantSimpleInfo
                        {
                            // Преобразуем numeric(11) в string
                            Snils = reader.GetDecimal(0).ToString(),

                            // numeric(1) преобразуем в bool (0 - false, 1 - true)
                            IsSubmitOriginal = reader.GetDecimal(1) == 1
                        };
                        if (appInfo.IsSubmitOriginal)
                        {
                            applicants[appInfo.Snils].ConfirmOrginalDocs();
                        }
                    }
                }
            }

            var subjects = new Dictionary<int, string>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                string sql = @"
                SELECT id, subject_name, min_treshold 
                FROM subjects";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var subject = new Subject
                        {
                            Id = reader.GetInt32(0),
                            SubjectName = reader.GetString(1),
                            MinThreshold = reader.GetInt32(2)
                        };
                        subjects.Add(subject.Id, subject.SubjectName);
                    }
                }
            }

            var areas = new Dictionary<int, string>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = "SELECT id, name FROM areas_of_study";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var area = new AreaShortInfo
                        {
                            Id = reader.GetInt32(0),
                            Name = reader.GetString(1)
                        };
                        areas.Add(area.Id, area.Name);
                    }
                }
            }

            Dictionary<string, string[]> prioritiesDict = new Dictionary<string, string[]>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = @"
                SELECT id, area_id, snils, priority_num 
                FROM priorities";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var pr = new ApplicantPriority
                        {
                            Id = reader.GetInt32(0),
                            AreaId = reader.GetInt32(1),
                            Snils = reader.GetDecimal(2).ToString(), // Преобразуем numeric(11) в string
                            PriorityNumber = reader.GetInt32(3)
                        };
                        if (!prioritiesDict.ContainsKey(pr.Snils))
                        {
                            prioritiesDict.Add(pr.Snils, new string[5]);
                        }

                        prioritiesDict[pr.Snils][pr.PriorityNumber - 1] = areas[pr.AreaId];
                    }
                }

            }

            foreach (ApplicantScore score in scores)
            {
                applicants[score.Snils].AddResult(subjects[score.SubjectId], score.TestBall);
            }
            foreach(string snils in prioritiesDict.Keys)
            {
                applicants[snils].SetPrioroties(prioritiesDict[snils]);
            }
            return applicants.Values.ToArray();
        }

        public Dictionary<string, Direction> LoadDirections()
        {
            var directions = new Dictionary<string, Direction>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = @"
                SELECT id, name, code, n_budget_places, n_fee_places, 
                       form_education, subject1_id, subject2_id, subject3_id,
                       subject4_id, subject5_id, subject6_id
                FROM areas_of_study";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var area = new AreaOfStudy
                        {
                            Id = reader.GetInt32(0),
                            Name = reader.GetString(1),
                            Code = reader.GetString(2),
                            BudgetPlaces = reader.GetInt32(3),
                            FeePlaces = reader.GetInt32(4),
                            FormEducation = reader.GetString(5),
                            Subject1Id = reader.GetInt32(6),
                            Subject2Id = reader.GetInt32(7),
                            Subject3Id = reader.GetInt32(8)
                        };

                        area.Subject4Id = !reader.IsDBNull(9) ? reader.GetInt32(9) : (int?)null;
                        area.Subject5Id = !reader.IsDBNull(10) ? reader.GetInt32(10) : (int?)null;
                        area.Subject6Id = !reader.IsDBNull(11) ? reader.GetInt32(11) : (int?)null;

                        directions.Add(area.Name, new Direction(area.Name, area.Code, area.BudgetPlaces, area.FeePlaces));

                    }
                }
            }
            return directions;
        }

        public Dictionary<string, string> GetDirectionsFullNames()
        {
            var directionsNames = new Dictionary<string, string>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = @"
                SELECT name, full_name, subject1_id, subject2_id, subject3_id
                FROM areas_of_study";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        string name = reader.GetString(0);
                        string full_name = reader.GetString(1);
                        directionsNames.Add(name, full_name);
                    }
                }
            }
            return directionsNames;
        }

        public Dictionary<string, string[]> GetDirectionsSubjects()
        {
            var directionsSubjects = new Dictionary<string, string[]>();

            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = @"
                SELECT name, subject1_id, subject2_id, subject3_id
                FROM areas_of_study";

                using (var command = new NpgsqlCommand(sql, connection))
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        string name = reader.GetString(0);
                        int subject1_id = reader.GetInt16(1);
                        int subject2_id = reader.GetInt16(2);
                        int subject3_id = reader.GetInt16(3);
                        directionsSubjects.Add(name, new string[] { GetSubjectNameById(subject1_id),  GetSubjectNameById(subject2_id), GetSubjectNameById(subject3_id) });
                    }
                }
            }
            return directionsSubjects;
        }

        public void GenerateAndInsertApplicants(int count)
        {
            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                for (int i = 0; i < count; i++)
                {
                    var applicant = GenerateRandomApplicant();
                    InsertApplicant(connection, applicant);
                }
            }
        }

        private ApplicantInsertionStruct GenerateRandomApplicant()
        {
            return new ApplicantInsertionStruct
            {
                Snils = GenerateRandomSnils(),
                PhoneNumber = GenerateRandomPhone(),
                Email = GenerateRandomEmail(),
                AchievementBall = _random.Next(0, 11), // от 0 до 9
                IsNeedDormitory = _random.Next(2) == 1, // 50% chance
                IsSubmitOriginal = _random.Next(2) // 0 или 1
            };
        }

        private string GenerateRandomSnils()
        {
            // Генерация 11-значного числа
            long snils = 10000000000L + _random.NextInt64(90000000000);
            return snils.ToString();
        }

        private string GenerateRandomPhone()
        {
            return "79" + _random.Next(100000000, 999999999).ToString();
        }

        private string GenerateRandomEmail()
        {
            string[] domains = { "mail.ru", "gmail.com", "yandex.ru", "test.ru" };
            string name = "user" + _random.Next(1000, 9999);
            return $"{name}@{domains[_random.Next(domains.Length)]}";
        }

        private void InsertApplicant(NpgsqlConnection connection, ApplicantInsertionStruct applicant)
        {
            const string sql = @"
            INSERT INTO applicants_data 
            (snils, phone_number, mail, achievment_ball, is_need_domitry, is_submit_original)
            VALUES (@snils, @phone, @email, @achievement, @dormitory, @original)";

            using (var command = new NpgsqlCommand(sql, connection))
            {
                command.Parameters.AddWithValue("@snils", decimal.Parse(applicant.Snils));
                command.Parameters.AddWithValue("@phone", decimal.Parse(applicant.PhoneNumber));
                command.Parameters.AddWithValue("@email", applicant.Email);
                command.Parameters.AddWithValue("@achievement", applicant.AchievementBall);
                command.Parameters.AddWithValue("@dormitory", applicant.IsNeedDormitory);
                command.Parameters.AddWithValue("@original", applicant.IsSubmitOriginal);

                command.ExecuteNonQuery();
            }

            for (int i = 1; i <= 3; i++)
            {
                const string sql2 = @"
                INSERT INTO applicants_scores 
                (subject_id, test_ball, snils)
                VALUES (@subjectId, @testBall, @snils)";

                    using (var command = new NpgsqlCommand(sql2, connection))
                    {
                        command.Parameters.AddWithValue("@subjectId", i);
                        command.Parameters.AddWithValue("@testBall", _random.Next(50, 100));
                        command.Parameters.AddWithValue("@snils", Int64.Parse(applicant.Snils));

                        command.ExecuteNonQuery();
                    }
            }
        }

        public string GetSubjectNameById(int subjectId)
        {
            using (var connection = new NpgsqlConnection(connectionString))
            {
                connection.Open();

                const string sql = "SELECT subject_name FROM subjects WHERE id = @subjectId";

                using (var command = new NpgsqlCommand(sql, connection))
                {
                    command.Parameters.AddWithValue("@subjectId", subjectId);

                    var result = command.ExecuteScalar();
                    return result?.ToString(); // Возвращаем null если предмет не найден
                }
            }
        }
    }
}
