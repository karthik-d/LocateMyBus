import pandas as pd
import numpy as np
import re

silver_lines = pd.read_csv('SL/OctToDec.csv')
intersecting_lines = pd.read_csv('Intersecting/OctToDec.csv')

TIMELOGS = pd.concat([silver_lines, intersecting_lines])
# ['Unnamed: 0', 'ServiceDate', 'Route', 'Direction', 'HalfTripId', 'Stop',
       # 'Timepoint', 'TimepointOrder', 'PointType', 'StandardType', 'Scheduled',
       # 'Actual', 'ScheduledHeadway', 'Headway']

STOPS = pd.read_csv('StaticGTFS/stops.txt')
# ['stop_id', 'stop_code', 'stop_name', 'stop_desc', 'platform_code',
       # 'platform_name', 'stop_lat', 'stop_lon', 'zone_id', 'stop_address',
       # 'stop_url', 'level_id', 'location_type', 'parent_station',
       # 'wheelchair_boarding', 'municipality', 'on_street', 'at_street',
       # 'vehicle_type']

# ROUTES = pd.read_csv('StaticGTFS/routes.txt')
# ['trip_id', 'arrival_time', 'departure_time', 'stop_id', 'stop_sequence',
       # 'stop_headsign', 'pickup_type', 'drop_off_type', 'timepoint',
       # 'checkpoint_id', 'continuous_pickup', 'continuous_drop_off']

silver_line_ids = list(map(lambda x: "SL"+str(x), [1, 2, 4, 5]))
intersecting_line_ids = ['01', '08', '09', '19', '47']

def like(x, pattern):
    r = re.compile(pattern)
    vlike = np.vectorize(lambda val: bool(r.fullmatch(val)))
    return vlike(x)

def save_silver_lines():
    complete = pd.read_csv("OctToDec.csv")
    # silver_lines = complete.loc[complete['route_id'].isin(silver_line_ids), :]
    lines = complete.loc[complete['Route'].isin(silver_line_ids), :]
    lines.to_csv("SL/OctToDec.csv", sep=',')

def save_intersecting_lines():
    complete = pd.read_csv("OctToDec.csv")
    # silver_lines = complete.loc[complete['route_id'].isin(silver_line_ids), :]
    lines = complete.loc[complete['Route'].isin(intersecting_line_ids), :]
    lines.to_csv("Intersecting/OctToDec.csv", sep=',')

def get_tripids_for_route(route_id):
    return TIMELOGS.loc[TIMELOGS['Route']==route_id, ['HalfTripId']].drop_duplicates()

def get_stopids_for_route(route_id):
    return TIMELOGS.loc[TIMELOGS['Route']==route_id, ['Stop']].drop_duplicates()

def get_stopids_for_trip(trip_id):
    return TIMELOGS.loc[TIMELOGS['HalfTripId']==trip_id, ['Stop', 'TimepointOrder']].sort_values(by=['TimepointOrder'])

def get_stop_details(stop_ids):
    # stop_id is a string here, because some some stops ids are textual
    # stop_code is numeric version of stop_id as float
    return STOPS.loc[STOPS['stop_code'].isin(stop_ids), :]

def get_stops_for_route(route_id, is_silverline=True):
    stop_ids = get_stopids_for_route(route_id)
    stop_details = get_stop_details(stop_ids['Stop'])
    stop_details.stop_code = stop_details.stop_code.astype(int)
    return pd.merge(stop_ids,
                    stop_details,
                    left_on='Stop',
                    right_on='stop_code')

def get_stops_for_trip(trip_id):
    stop_ids = get_stopids_for_trip(trip_id)
    stop_details = get_stop_details(stop_ids['Stop'])
    stop_details.stop_code = stop_details.stop_code.astype(int)
    return pd.merge(stop_ids,
                    stop_details,
                    left_on='Stop',
                    right_on='stop_code')

def get_stopsequence_for_trip(trip_id):
    return STOP_TIMES.loc[STOP_TIMES['trip_id']==str(int(trip_id)), ['stop_id', 'stop_sequence']].sort_values(by=['stop_sequence'])


"""
for r_id in silver_line_ids:
    print()
    print(r_id, "-"*50)
    trip_ids = get_tripids_for_route(r_id)['HalfTripId'].tolist()
    print(get_stops_for_trip(trip_ids[1]).loc[:, ['stop_code', 'stop_name']])
"""

'''
print(19, "-"*50)
trip_ids = get_tripids_for_route(47)['HalfTripId'].tolist()
ctr = 0
print(len(trip_ids))
for trip_id in trip_ids:
    stops = get_stops_for_trip(trip_id).loc[:, ['TimepointOrder', 'stop_code', 'stop_name', 'stop_url']]
    # print(stops.shape)

    if(stops.shape[0]==8):
        print(stops)
        ctr += 1
print(ctr)
'''


print('SL4', "-"*50)
trip_ids = get_tripids_for_route('SL4')['HalfTripId'].tolist()
print(trip_ids)
print(get_stops_for_trip(trip_ids[1]).loc[:, ['stop_code', 'stop_name']])


# print(TIMELOGS.columns)

# save_silver_lines()
# save_intersecting_lines()

# print(silver_lines.shape)
#print(TIMELOGS.columns)
#print(check.shape)
#print(check.tail(100))

#routes = pd.read_csv("routes.txt")
#bus_routes = routes.loc[like(routes['route_desc'], '.*Bus.*'), ['route_id', 'route_short_name', 'route_desc']]
#print(bus_routes.shape)
#print(bus_routes)
#bus_routes = routes.query('route_type.str.contains("Bus")')
#print(bus_routes)
#print(bus_routes.shape)
