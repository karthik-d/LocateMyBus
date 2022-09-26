import pandas as pd

'''
SILVER_LINES = pd.read_csv("SL/OctToDec.csv", dtype={'HalfTripId':'string',    # Because it is a float
                                                        'Stop':'string',
                                                        'TimepointOrder':'int',
                                                        'Route':'string',
                                                        'Scheduled':'string',
                                                        'Actual':'string',
                                                        'Direction':'string'
                                                        })
INTERSECTING_LINES = pd.read_csv("Intersecting/OctToDec.csv", dtype={'HalfTripId':'string',    # Because it is a float
                                                        'Stop':'string',
                                                        'TimepointOrder':'int',
                                                        'Route':'string',
                                                        'Scheduled':'string',
                                                        'Actual':'string',
                                                        'Direction':'string'
                                                        })
'''
# TIMELOGS = pd.concat([SILVER_LINES, INTERSECTING_LINES])

# TIMELOGS = pd.read_csv('OctToDec.csv', nrows=None, dtype={'HalfTripId':'string',    # Because it is a float
                                                        # 'Stop':'string',
                                                        # 'TimepointOrder':'int',
                                                        # 'Route':'string',
                                                        # 'Scheduled':'string',
                                                        # 'Actual':'string',
                                                        # 'Direction':'string'
                                                        # })

RELEVANT_TIMELOGS = pd.read_csv("Segment1/RelevantStopsIn.csv", dtype={'HalfTripId':'string',    # Because it is a float
                                                                        'Stop':'string',
                                                                        'TimepointOrder':'int',
                                                                        'Route':'string',
                                                                        'Scheduled':'string',
                                                                        'Actual':'string',
                                                                        'Direction':'string'
                                                                        })

# print(TIMELOGS)
# ['Unnamed: 0', 'ServiceDate', 'Route', 'Direction', 'HalfTripId', 'Stop',
       # 'Timepoint', 'TimepointOrder', 'PointType', 'StandardType', 'Scheduled',
       # 'Actual', 'ScheduledHeadway', 'Headway']

def save_relevant_stops(stop_ids, direction="Inbound"):
    stops = TIMELOGS.loc[(TIMELOGS['Stop'].isin(stop_ids)) & (TIMELOGS['Direction']==direction),
                            ['ServiceDate', 'Route', 'HalfTripId', 'Stop', 'TimepointOrder', 'Scheduled', 'Actual']].sort_values(
                            by=['ServiceDate', 'Route', 'HalfTripId', 'TimepointOrder'])
    print(stops.shape)
    stops.to_csv("Segment1/RelevantStopsIn.csv", sep=",")

print(RELEVANT_TIMELOGS.head())

# save_relevant_stops(['64', '3'])
