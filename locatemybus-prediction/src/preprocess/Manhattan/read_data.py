import pandas as pd
import re
import numpy as np

cal_dates = pd.read_csv("calendar_dates.txt")

# ['route_id', 'trip_id', 'service_id', 'trip_headsign', 'direction_id', 'shape_id', 'drt_advance_book_min', 'peak_offpeak']
trips = pd.read_csv("trips.txt")

# ['trip_id', 'stop_id', 'arrival_time', 'departure_time', 'stop_sequence', 'pickup_type', 'drop_off_type', 'continuous_pickup',
# 'continuous_drop_off', 'start_service_area_radius', 'end_service_area_radius', 'departure_buffer']
stop_times = pd.read_csv("stop_times.txt")

# ['stop_id', 'stop_name', 'stop_lat', 'stop_lon']
stops = pd.read_csv("stops.txt")

complete_stops = stop_times.merge(stops, on='stop_id')
stops_with_service = complete_stops.merge(trips, on='trip_id')

def like(x, pattern):
    r = re.compile(pattern)
    vlike = np.vectorize(lambda val: bool(r.fullmatch(val)))
    return vlike(x)

def trips_for_routes(route_ids):
    # direction_id taken as 1 consistently
    result = trips.loc[trips['route_id'].isin(route_ids) & trips['direction_id']==1
                        , ['trip_id', 'route_id', 'service_id', 'direction_id']]
    return result

def stoptimes_for_trip(trip_id):
    result = complete_stops.loc[complete_stops['trip_id']==trip_id, ['stop_id', 'stop_name', 'stop_sequence', 'departure_time']].sort_values(by=['stop_sequence'])
    return result



#print(trips.head())
#print(stops.columns)
#print(trips[trips.route_id.isin(['M7', 'M5', 'M104'])].groupby('trip_id'))
#stop_ids = stop_times.loc[stop_times['trip_id']=='MV_A1-Saturday-001000_M104_102', ['stop_id']].sort_values(by=['stop_id'])
#stop_ids = stop_times.loc[stop_times['trip_id']=='MV_A1-Saturday-001000_M104_102', ['stop_id', 'arrival_time', 'departure_time', 'stop_sequence']].sort_values(by=['stop_sequence'])
#print(stop_ids + )

#print(trips_for_routes(['M7', 'M5', 'M104']).groupby('trip_id').head(20))

#x = trips_for_routes(['M106'])
#service_ids = x.loc[:, ['service_id']].drop_duplicates()

#x.groupby('service_id')

#x = stops_with_service.loc[stops_with_service['route_id'].isin(['M106','M2','M3','M4']), :].groupby(['service_id', 'route_id', 'trip_id']).agg('count')
#print(x.tail(100))

#y = stops_with_service.loc[stops_with_service['route_id'].isin(['M106'])``````,
                            #['trip_id', 'route_id']].sort_values(by='trip_id')
#print(y.tail(40))


#print(stoptimes_for_trip('MV_H1-Weekday-144500_M2_242').tail(60))
#print(stoptimes_for_trip('MV_H1-Weekday-130600_M2_240').tail(60))
# print(stoptimes_for_trip('MV_H1-Weekday-131700_M4_453').tail(60))
#print(stoptimes_for_trip('MV_H1-Weekday-119000_M96_826').tail(60))
#print(stoptimes_for_trip('MV_A1-Saturday-065500_M96_807').tail(60))
#print(stoptimes_for_trip('OH_H1-Weekday-BM-143400_M101_1').tail(60))

#print(stops[stops.stop_id.isin(stop_ids['stop_id'])])
#print(trips.loc[:, ['route_id']].drop_duplicates())
print(trips.shape)
#print(cal_dates.sort_values('date'))

trips_needed = trips_for_routes(['M106', 'M4', 'M3', 'M2'])
print(trips_needed.shape)
