using api.models;
using api.Structures;
using Microsoft.AspNetCore.Mvc;
using System.Data;
using System.Text.Json;

namespace api
{
    public class Logic
    {
        ApplicantsBase appBase;
        Database db;

        public Logic()
        {
            appBase = new ApplicantsBase();
            db = new Database();
            appBase.Init();
            CalcFinalList();
            //db.GenerateAndInsertApplicants(1);
        }
        public IActionResult GetAllDirections()
        {
            Dictionary<string, string> names = db.GetDirectionsFullNames();
            Dictionary<string, string[]> subjects = db.GetDirectionsSubjects();
            var result = new List<object>();

            foreach (var name in names.Keys)
            {
                result.Add(new
                {
                    Name = name,
                    Value = names[name],
                    prOne = subjects[name][0],
                    prTwo = subjects[name][1],
                    prThree = subjects[name][2]
                });
            }

            var options = new JsonSerializerOptions
            {
                WriteIndented = true,
                Encoder = System.Text.Encodings.Web.JavaScriptEncoder.UnsafeRelaxedJsonEscaping,
                PropertyNamingPolicy = JsonNamingPolicy.CamelCase
            };

            return new JsonResult(result, options);
        }
        public IActionResult GetDirectionList(string direction)
        {
            Applicant[] applicants = appBase.GetDirectionList(direction);
            var result = new List<object>();

            foreach (Applicant applicant in applicants)
            {
                string[] priorities = applicant.Priorities;
                result.Add(new
                {
                    Snils = applicant.Snils,
                    BallAmount = applicant.BallAmount,
                    Priority = Array.IndexOf(priorities, direction) + 1
                });
            }

            result.Reverse();

            var options = new JsonSerializerOptions
            {
                WriteIndented = true,
                Encoder = System.Text.Encodings.Web.JavaScriptEncoder.UnsafeRelaxedJsonEscaping,
                PropertyNamingPolicy = JsonNamingPolicy.CamelCase
            };

            return new JsonResult(result, options);
        }

        public IActionResult GetDirectionOriginals(string direction)
        {
            Applicant[] applicants = appBase.GetDirectionOriginals(direction);
            var result = new List<object>();

            foreach (Applicant applicant in applicants)
            {
                string[] priorities = applicant.Priorities;
                result.Add(new
                {
                    Snils = applicant.Snils,
                    BallAmount = applicant.BallAmount,
                    Priority = Array.IndexOf(priorities, direction) + 1
                });
            }

            result.Reverse();

            var options = new JsonSerializerOptions
            {
                WriteIndented = true,
                Encoder = System.Text.Encodings.Web.JavaScriptEncoder.UnsafeRelaxedJsonEscaping,
                PropertyNamingPolicy = JsonNamingPolicy.CamelCase
            };

            return new JsonResult(result, options);
        }

        public void CalcFinalList()
        {
            SortedSet<Applicant> allApplicants = new SortedSet<Applicant>();
            Dictionary<string, Direction> directions = appBase.DirectionsDictionary;
            foreach (Direction d in appBase.DirectionsArray)
            {
                foreach (Applicant app in d.Applicants)
                {
                    if (app.IsSubmitOriginalDocs)
                    {
                        allApplicants.Add(app);
                    }
                }
            }

            foreach (Applicant app in allApplicants)
            {
                bool used = false;
                foreach (string priority in app.Priorities)
                {
                    if (directions[priority].FinalList.Count() < directions[priority].FreePlaces)
                    {
                        directions[priority].FinalList.Add(app);
                        used = true;
                        break;
                    }
                }
                if (!used)
                {
                    foreach (string priority in app.Priorities)
                    {
                        if (directions[priority].FinalList.Count() - directions[priority].FreePlaces < directions[priority].FeePlaces)
                        {
                            directions[priority].FinalList.Add(app);
                        }
                    }
                }
            }
        }

        public IActionResult GetDirectionFinalList(string direction)
        { 

            var applicants = appBase.DirectionsDictionary[direction].FinalList;
            var result = new List<object>();

            foreach (var applicant in applicants)
            {
                result.Add(new
                {
                    Snils = applicant.Snils,
                    BallAmount = applicant.BallAmount,
                    Priority = Array.IndexOf(applicant.Priorities, direction) + 1
                });
            }

            result.Reverse();

            var options = new JsonSerializerOptions
            {
                WriteIndented = true,
                Encoder = System.Text.Encodings.Web.JavaScriptEncoder.UnsafeRelaxedJsonEscaping,
                PropertyNamingPolicy = JsonNamingPolicy.CamelCase
            };

            return new JsonResult(result, options);
        }
    }
}
